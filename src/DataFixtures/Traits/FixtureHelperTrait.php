<?php

namespace App\DataFixtures\Traits;

use Doctrine\Common\DataFixtures\ReferenceRepository;

trait FixtureHelperTrait
{

    public function getAllReferencesByPrefix(ReferenceRepository $referenceRepository, string $prefix): array
    {
        $results = [];
//        dump($referenceRepository->getReferencesByClass());
        foreach ($referenceRepository->getReferencesByClass() as $key => $object) {
            if (str_starts_with($key, $prefix)) {
                $results[$key] = $object;
            }
        }

        return $results;
    }
}