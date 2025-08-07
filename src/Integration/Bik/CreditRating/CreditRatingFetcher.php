<?php

namespace src\Integration\Bik\CreditRating;

use lib\ApiIntegrator\HttpMethod;
use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfoFactory;
use src\Entity\Issuer\CreditRating\CreditRatingInfo;
use src\Entity\Issuer\CreditRating\CreditRatingInfoFactory;
use src\Entity\Issuer\EsgRating\EsgRatingInfoFactory;
use src\Integration\Bik\BikHttpClient;

class CreditRatingFetcher
{
    public const string PATH = '/wp-admin/admin-ajax.php';

    public function __construct(
        private BikHttpClient $httpClient,
        private CreditRatingInfoFactory $factory,
    ) {
    }

    public function updateRatings(): void
    {
        $page = 1;
        $issuers = [];

        do {
            $response = $this->httpClient->request(HttpMethod::POST, self::PATH, [
                'action' => 'table_rate_ajax',
                'page' => $page,
            ]);
            $dom = new HtmlDocument($response->getContent());

            $issuerBlocks = $dom->getElementsByTagName('.raiting-row.rt');
            /** @var HtmlNode $issuerBlock */
            foreach ($issuerBlocks as $issuerBlock) {
                $issuers[] = new BikCreditRatingDto(
                    issuerName: $issuerBlock->find('.name')[0]->innertext(),
                    // Не опечатка, forecat у них на сайте
                    forecast: $issuerBlock->find('.forecat')[0]->innertext(),
                    rating: $issuerBlock->find('.rate')[0]->innertext(),
                    assignmentDate: $issuerBlock->find('.date')[0]->innertext(),
                    lastUpdateDate: $issuerBlock->find('.date')[1]->innertext(),
                    pressReleaseLink: $issuerBlock->find('.press a')[0]->getAttribute('href'),
                );
            }

            if (count($issuers) > 50 || empty($issuerBlocks)) {
                $this->factory->createOrUpdateMany(...$issuers);
                $issuers = [];
            }

            $page++;
        } while (!empty($issuerBlocks));
    }
}