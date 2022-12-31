<?php

namespace Modules\Quote\Http\Controllers;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Quote\Http\Requests\CheckQuotationCodeRequest;
use Modules\Quote\Http\Requests\ClientQuoteRequest;
use Modules\Quote\Http\Requests\QuoteTemplateRequest;
use Modules\Quote\Http\Requests\SectionRequest;
use Modules\Quote\Models\QuoteSection;
use Modules\Quote\Models\QuoteTemplate;
use Modules\Quote\Repositories\Interfaces\IQuoteRepository;

class QuoteController extends Controller
{
    use ApiResponseTrait;
    public function __construct(IQuoteRepository $quote)
    {
        $this->quote = $quote;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SectionRequest $request,$templateId)
    {
        $result = $this->quote->createSection($request->validated(),$templateId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show()
    {
        $result = $this->quote->getQuoteTemplate();
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(QuoteTemplateRequest $request, $templateId)
    {
        $result = $this->quote->updateTemplate($request->validated(),$templateId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        QuoteSection::find($id)->delete();
        return $this->apiResponse(null,true,'155001',200,'Quote template section deleted successfully');
    }

    public function generateClientQuote($clientId)
    {
        $result = $this->quote->generateQuote($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function saveClientQuote(ClientQuoteRequest $request,$clientId)
    {
        $result = $this->quote->saveClientQuote($clientId,$request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function sendClientQuote($clientId)
    {
        $result = $this->quote->sendClientQuote($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function showClientQuote($clientId)
    {
        $result = $this->quote->getClientQuote($clientId);
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function checkQuoteLink(CheckQuotationCodeRequest $request)
    {
        $result = $this->quote->checkQuotationLink($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }

    public function ClientLink(CheckQuotationCodeRequest $request)
    {
        $result = $this->quote->clientDecision($request->validated());
        return $this->apiResponse($result['data'] , $result['status'] , $result['identifier_code'] , $result['status_code'] , $result['message']);
    }
}
