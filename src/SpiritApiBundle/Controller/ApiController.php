<?php

namespace Edcoms\SpiritApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    const POSTCODE_REGEX = '/[A-Za-z]{1,2}[0-9][A-Za-z0-9]?(?:\s?[0-9][A-Za-z]{2}|$)/';

    /**
     * @Route("/organisation/search/{postcode}/{searchType}", name="spirit_api.organisation_postcode_search", defaults={"searchType": 1})
     *
     * @param   Request     $request    The incoming request.
     * @param   string      $postcode   The postcode criteria used to search organisations.
     * @param   int         $searchType restrict the returned results by group type, 1 is usually all establishments
     *
     * @return  Response            Response object to send back to the client.
     */
    public function organisationPostcodeSearchAction(Request $request, string $postcode, int $searchType): Response
    {
        if (preg_match(self::POSTCODE_REGEX, $postcode) !== 1) {
            return new JsonResponse('Invalid postcode', JsonResponse::HTTP_BAD_REQUEST);
        }

        $response = $this->get('spirit_api.organisation')->searchByPostcode($postcode, $searchType);

        return new JsonResponse($response);
    }
}
