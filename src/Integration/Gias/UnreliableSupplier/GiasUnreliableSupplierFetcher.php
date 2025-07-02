<?php

namespace src\Integration\Gias\UnreliableSupplier;

use lib\ApiIntegrator\HttpMethod;
use src\Entity\Issuer\PayerIdentificationNumber;
use src\Entity\Issuer\UnreliableSupplier\UnreliableSupplier;
use src\Integration\Gias\GiasHttpClient;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GiasUnreliableSupplierFetcher
{
    public function __construct(
        private GiasHttpClient $giasHttpClient,
        private SerializerInterface $serializer,
    ) {
    }

    public function update(): void
    {
        $page = 0;
        $pageSize = 200;

        do {
            $response = $this->giasHttpClient->request(
                method: HttpMethod::GET,
                path: GiasHttpClient::UNRELIABLE_SUPPLIER_LIST,
                queryParams: [
                    'page' => $page,
                    'size' => $pageSize,
                ],
            );

            /** @var GiasUnreliableSupplierListDto $listDto */
            $listDto = $this->serializer->deserialize(
                $response->getContent(),
                GiasUnreliableSupplierListDto::class,
                'json',
                [
                    AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                    AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
                ]
            );

            foreach ($listDto->content as $supplierDto) {
                $model = UnreliableSupplier::createOrUpdate(
                    pid: new PayerIdentificationNumber($supplierDto->pid),
                    uuid: $supplierDto->uuid,
                    chainUuid: $supplierDto->chainUuid,
                    authorUuid: $supplierDto->authorUuid,
                    authorInitials: $supplierDto->authorInitials,
                    state: $supplierDto->state,
                    issuerName: $supplierDto->issuerName,
                    issuerAddress: $supplierDto->issuerAddress,
                    registrationNumber: $supplierDto->registrationNumber,
                    addDate: $supplierDto->addDate,
                    deleteDate: $supplierDto->deleteDate,
                    reason: $supplierDto->reason
                );
                $model->save();
            }

            $page++;
        } while (!empty($listDto->content) && $listDto->last === false);
    }
}