<?php

namespace src\Integration\Bik\BusinessReputation;

use lib\ApiIntegrator\HttpMethod;
use simplehtmldom\HtmlDocument;
use simplehtmldom\HtmlNode;
use src\Entity\Issuer\BusinessReputationRating\BusinessReputationInfoFactory;
use src\Integration\Bik\BikHttpClient;

class BusinessReputationRatingFetcher
{
    public const string PATH = '/wp-admin/admin-ajax.php';

    public function __construct(
        private BikHttpClient $httpClient,
        private BusinessReputationInfoFactory $factory,
    ) {
    }

    public function updateRatings(): void
    {
        $page = 1;
        $issuers = [];

        do {
            $response = $this->httpClient->request(HttpMethod::POST, self::PATH, [
                'action' => 'table_rate_dr_ajax',
                'page' => $page,
            ]);
            $dom = new HtmlDocument($response->getContent());

            $issuerBlocks = $dom->getElementsByTagName('.raiting-row.rt');
            /** @var HtmlNode $issuerBlock */
            foreach ($issuerBlocks as $issuerBlock) {
                $issuers[] = new BikIssuerBusinessReputationDto(
                    pid: $issuerBlock->find('.unp')[0]->innertext(),
                    issuerName: $issuerBlock->find('.full_name')[0]->childNodes(0)->innertext(),
                    rating: $issuerBlock->find('.rating_dr')[0]->innertext(),
                    expirationDate: $issuerBlock->find('.date_s')[1]->innertext(),
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