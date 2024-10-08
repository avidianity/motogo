<?php

namespace App\Processors;

use App\Enums\Role;
use App\Exceptions\Processors\RiderRegistrationException;
use App\Interfaces\Processor;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Models;
use Illuminate\Support\Facades\DB;

class RiderRegistration implements Processor
{
    protected ?File $driversLicense = null;
    protected ?File $vehicleRegistration = null;
    protected ?array $data = null;
    protected Filesystem $storage;

    public function __construct()
    {
        $this->storage = Storage::build(config('registration.rider.storage'));
    }

    public function withDriversLicense(File $file)
    {
        $this->driversLicense = $file;

        return $this;
    }

    public function withVehicleRegistration(File $file)
    {
        $this->vehicleRegistration = $file;

        return $this;
    }

    public function withData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function process()
    {
        if (!$this->driversLicense) {
            throw new RiderRegistrationException("Driver's License is required");
        }

        if (!$this->vehicleRegistration) {
            throw new RiderRegistrationException('Vehicle Registration is required');
        }

        if (!$this->data) {
            throw new RiderRegistrationException('Data is required');
        }

        return DB::transaction(function () {
            try {
                /**
                 * @var Models\User
                 */
                $rider = Models\User::make($this->data)->fill(['role'=> Role::RIDER]);

                $rider->save();

                $license = Models\DriversLicense::make([
                    'file_id' => $this->processFile($this->driversLicense, $rider->getKey())->getKey()
                ]);

                $registration = Models\VehicleRegistration::make([
                    'file_id' => $this->processFile($this->vehicleRegistration, $rider->getKey())->getKey()
                ]);

                $rider->license()->save($license);
                $rider->registration()->save($registration);
                $rider->setRelations([
                    'license' => $license,
                    'registration' => $registration,
                ]);

                return $rider;
            } catch (Exception $e) {
                throw new RiderRegistrationException($e->getMessage(), $e);
            }
        });
    }

    protected function processFile(File $file, $path)
    {
        $this->storage->put($path, $file);

        return Models\File::create([
            'name' => $file->getFilename(),
            'type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
            'driver' => config('registration.rider.storage.driver'),
        ]);
    }
}
