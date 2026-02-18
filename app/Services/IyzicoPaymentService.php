<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyCommissionPayment;
use App\Models\PaymentRequest;
use App\Models\Setting;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Payment;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;

class IyzicoPaymentService
{
    public static function isEnabled(): bool
    {
        $v = Setting::get('payment_enabled', '0');
        return $v === '1' || $v === true;
    }

    public static function getOptions(): Options
    {
        $options = new Options();
        $options->setApiKey(Setting::get('iyzico_api_key', ''));
        $options->setSecretKey(Setting::get('iyzico_secret_key', ''));
        $baseUrl = (bool) Setting::get('iyzico_sandbox', true)
            ? 'https://sandbox-api.iyzipay.com'
            : 'https://api.iyzipay.com';
        $options->setBaseUrl($baseUrl);
        return $options;
    }

    /**
     * Borç ödemesi için checkout form başlatır.
     * @return array{success: bool, token?: string, checkout_form_content?: string, error?: string}
     */
    public static function initializeBorcPayment(Company $company, float $amount): array
    {
        $conversationId = 'borc_' . $company->id . '_' . uniqid();
        $paymentRequest = PaymentRequest::create([
            'company_id' => $company->id,
            'type' => PaymentRequest::TYPE_BORC,
            'amount' => round($amount, 2),
            'currency' => 'TRY',
            'conversation_id' => $conversationId,
            'status' => PaymentRequest::STATUS_PENDING,
        ]);
        return self::initializeCheckout(
            $company,
            round($amount, 2),
            $conversationId,
            'BORC_' . $paymentRequest->id,
            'NakliyePark komisyon borcu ödemesi',
            route('nakliyeci.odeme.callback')
        );
    }

    /**
     * Paket satın alma için checkout form başlatır.
     * @return array{success: bool, token?: string, checkout_form_content?: string, error?: string}
     */
    public static function initializePackagePayment(Company $company, string $packageId, float $amount, string $packageName): array
    {
        $conversationId = 'paket_' . $company->id . '_' . $packageId . '_' . uniqid();
        $paymentRequest = PaymentRequest::create([
            'company_id' => $company->id,
            'type' => PaymentRequest::TYPE_PAKET,
            'amount' => round($amount, 2),
            'currency' => 'TRY',
            'conversation_id' => $conversationId,
            'package_id' => $packageId,
            'status' => PaymentRequest::STATUS_PENDING,
        ]);
        return self::initializeCheckout(
            $company,
            round($amount, 2),
            $conversationId,
            'PAKET_' . $paymentRequest->id,
            'NakliyePark ' . $packageName . ' paketi',
            route('nakliyeci.odeme.callback')
        );
    }

    /**
     * @return array{success: bool, token?: string, checkout_form_content?: string, error?: string}
     */
    private static function initializeCheckout(
        Company $company,
        float $amount,
        string $conversationId,
        string $basketId,
        string $basketItemName,
        string $callbackUrl
    ): array {
        $options = self::getOptions();
        $user = $company->user;
        $price = number_format($amount, 2, '.', '');
        $paidPrice = $price;

        $request = new CreateCheckoutFormInitializeRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPrice($price);
        $request->setPaidPrice($paidPrice);
        $request->setCurrency(Currency::TL);
        $request->setBasketId($basketId);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl($callbackUrl);

        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName(mb_substr($user->name ?? 'Kullanici', 0, 255));
        $buyer->setSurname(' ');
        $buyer->setGsmNumber($user->phone ?? $company->phone ?? '+905550000000');
        $buyer->setEmail($user->email ?? '');
        $buyer->setIdentityNumber('11111111111');
        $buyer->setRegistrationAddress($company->address ?? 'Türkiye');
        $buyer->setIp(request()->ip() ?? '127.0.0.1');
        $buyer->setCity($company->city ?? 'Istanbul');
        $buyer->setCountry('Turkey');
        $buyer->setZipCode('34000');
        $request->setBuyer($buyer);

        $address = new Address();
        $address->setContactName($user->name ?? $company->name ?? 'Alıcı');
        $address->setCity($company->city ?? 'Istanbul');
        $address->setCountry('Turkey');
        $address->setAddress($company->address ?? 'Türkiye');
        $address->setZipCode('34000');
        $request->setShippingAddress($address);
        $request->setBillingAddress($address);

        $basketItem = new BasketItem();
        $basketItem->setId('1');
        $basketItem->setName($basketItemName);
        $basketItem->setCategory1('Hizmet');
        $basketItem->setCategory2('Abonelik');
        $basketItem->setItemType(BasketItemType::VIRTUAL);
        $basketItem->setPrice($price);
        $request->setBasketItems([$basketItem]);

        try {
            $result = CheckoutFormInitialize::create($request, $options);
            if ($result->getStatus() === 'success' && $result->getToken()) {
                return [
                    'success' => true,
                    'token' => $result->getToken(),
                    'checkout_form_content' => $result->getCheckoutFormContent(),
                    'payment_page_url' => $result->getPaymentPageUrl(),
                ];
            }
            return ['success' => false, 'error' => $result->getErrorMessage() ?? 'Ödeme başlatılamadı.'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Callback'ten gelen token ile ödeme sonucunu alır ve kaydeder.
     * @return array{success: bool, payment_request?: PaymentRequest, error?: string, redirect_url?: string}
     */
    public static function retrieveAndComplete(string $token): array
    {
        $options = self::getOptions();
        $request = new RetrieveCheckoutFormRequest();
        $request->setLocale(Locale::TR);
        $request->setToken($token);

        try {
            $result = CheckoutForm::retrieve($request, $options);
            $conversationId = $result->getConversationId();
            $paymentRequest = PaymentRequest::where('conversation_id', $conversationId)->first();

            if (! $paymentRequest) {
                return ['success' => false, 'error' => 'Ödeme kaydı bulunamadı.'];
            }

            if ($paymentRequest->status === PaymentRequest::STATUS_COMPLETED) {
                return [
                    'success' => true,
                    'payment_request' => $paymentRequest,
                    'redirect_url' => $paymentRequest->type === PaymentRequest::TYPE_BORC
                        ? route('nakliyeci.borc.index')
                        : route('nakliyeci.paketler.index'),
                ];
            }

            if ($result->getPaymentStatus() !== 'SUCCESS') {
                $paymentRequest->update(['status' => PaymentRequest::STATUS_FAILED]);
                return ['success' => false, 'error' => $result->getErrorMessage() ?? 'Ödeme başarısız.'];
            }

            $company = $paymentRequest->company;
            $paidPrice = (float) $result->getPaidPrice();

            if ($paymentRequest->type === PaymentRequest::TYPE_BORC && $paidPrice < (float) $paymentRequest->amount) {
                $paymentRequest->update(['status' => PaymentRequest::STATUS_FAILED]);
                return ['success' => false, 'error' => 'Ödeme tutarı yetersiz.'];
            }

            \DB::transaction(function () use ($paymentRequest, $company, $paidPrice, $result, $conversationId, $token) {
                if ($paymentRequest->type === PaymentRequest::TYPE_BORC) {
                    CompanyCommissionPayment::create([
                        'company_id' => $company->id,
                        'amount' => $paidPrice,
                        'currency' => $result->getCurrency() ?? 'TRY',
                        'gateway' => 'iyzico',
                        'transaction_id' => $result->getPaymentId(),
                        'conversation_id' => $conversationId,
                        'meta' => ['token' => $token],
                        'paid_at' => now(),
                    ]);
                } else {
                    $company->update(['package' => $paymentRequest->package_id]);
                }

                $paymentRequest->update([
                    'status' => PaymentRequest::STATUS_COMPLETED,
                    'gateway_transaction_id' => $result->getPaymentId(),
                    'completed_at' => now(),
                ]);
            });

            return [
                'success' => true,
                'payment_request' => $paymentRequest->fresh(),
                'redirect_url' => $paymentRequest->type === PaymentRequest::TYPE_BORC
                    ? route('nakliyeci.borc.index')
                    : route('nakliyeci.paketler.index'),
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Admin manuel ödeme: kart bilgileri ile doğrudan iyzico'ya ödeme gönderir.
     * Kart bilgileri sunucuda saklanmaz, sadece iyzico API'ye iletilir.
     *
     * @param  array{card_holder_name: string, card_number: string, expire_month: string, expire_year: string, cvc: string}  $card
     * @return array{success: bool, payment_request?: PaymentRequest, error?: string}
     */
    public static function createAdminDirectPayment(Company $company, float $amount, string $type, array $card, ?string $packageId = null, ?string $packageName = null): array
    {
        if (! self::isEnabled()) {
            return ['success' => false, 'error' => 'Ödeme sistemi kapalı. Ayarlardan ödemeleri açın.'];
        }
        if (! in_array($type, [PaymentRequest::TYPE_BORC, PaymentRequest::TYPE_PAKET], true)) {
            return ['success' => false, 'error' => 'Geçersiz ödeme türü.'];
        }
        if ($type === PaymentRequest::TYPE_PAKET && (! $packageId || ! $packageName)) {
            return ['success' => false, 'error' => 'Paket seçin.'];
        }

        $conversationId = 'admin_' . $type . '_' . $company->id . '_' . uniqid();
        $basketId = strtoupper($type) . '_' . $company->id . '_' . time();

        $paymentRequest = PaymentRequest::create([
            'company_id' => $company->id,
            'type' => $type,
            'amount' => round($amount, 2),
            'currency' => 'TRY',
            'conversation_id' => $conversationId,
            'package_id' => $type === PaymentRequest::TYPE_PAKET ? $packageId : null,
            'status' => PaymentRequest::STATUS_PENDING,
        ]);

        $options = self::getOptions();
        $user = $company->user;
        $price = number_format(round($amount, 2), 2, '.', '');

        $request = new CreatePaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($conversationId);
        $request->setPrice($price);
        $request->setPaidPrice($price);
        $request->setCurrency(Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($basketId);
        $request->setPaymentChannel(PaymentChannel::WEB);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);

        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($card['card_holder_name'] ?? '');
        $paymentCard->setCardNumber(preg_replace('/\s+/', '', $card['card_number'] ?? ''));
        $paymentCard->setExpireMonth(str_pad((string) ($card['expire_month'] ?? ''), 2, '0', STR_PAD_LEFT));
        $paymentCard->setExpireYear((string) ($card['expire_year'] ?? ''));
        $paymentCard->setCvc((string) ($card['cvc'] ?? ''));
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName(mb_substr($user->name ?? 'Kullanici', 0, 255));
        $buyer->setSurname(' ');
        $buyer->setGsmNumber($user->phone ?? $company->phone ?? '+905550000000');
        $buyer->setEmail($user->email ?? '');
        $buyer->setIdentityNumber('11111111111');
        $buyer->setRegistrationAddress($company->address ?? 'Türkiye');
        $buyer->setIp(request()->ip() ?? '127.0.0.1');
        $buyer->setCity($company->city ?? 'Istanbul');
        $buyer->setCountry('Turkey');
        $buyer->setZipCode('34000');
        $request->setBuyer($buyer);

        $address = new Address();
        $address->setContactName($user->name ?? $company->name ?? 'Alıcı');
        $address->setCity($company->city ?? 'Istanbul');
        $address->setCountry('Turkey');
        $address->setAddress($company->address ?? 'Türkiye');
        $address->setZipCode('34000');
        $request->setShippingAddress($address);
        $request->setBillingAddress($address);

        $basketItemName = $type === PaymentRequest::TYPE_BORC
            ? 'NakliyePark komisyon borcu ödemesi'
            : ('NakliyePark ' . ($packageName ?? '') . ' paketi');
        $basketItem = new BasketItem();
        $basketItem->setId('1');
        $basketItem->setName($basketItemName);
        $basketItem->setCategory1('Hizmet');
        $basketItem->setCategory2('Abonelik');
        $basketItem->setItemType(BasketItemType::VIRTUAL);
        $basketItem->setPrice($price);
        $request->setBasketItems([$basketItem]);

        try {
            $result = Payment::create($request, $options);
            if ($result->getStatus() !== 'success') {
                $paymentRequest->update(['status' => PaymentRequest::STATUS_FAILED]);

                return ['success' => false, 'error' => $result->getErrorMessage() ?? 'Ödeme reddedildi.'];
            }

            $paidPrice = (float) $result->getPaidPrice();
            if ($type === PaymentRequest::TYPE_BORC && $paidPrice < (float) $paymentRequest->amount) {
                $paymentRequest->update(['status' => PaymentRequest::STATUS_FAILED]);

                return ['success' => false, 'error' => 'Ödeme tutarı yetersiz.'];
            }

            \DB::transaction(function () use ($paymentRequest, $company, $paidPrice, $result, $conversationId, $type) {
                if ($type === PaymentRequest::TYPE_BORC) {
                    CompanyCommissionPayment::create([
                        'company_id' => $company->id,
                        'amount' => $paidPrice,
                        'currency' => $result->getCurrency() ?? 'TRY',
                        'gateway' => 'iyzico',
                        'transaction_id' => $result->getPaymentId(),
                        'conversation_id' => $conversationId,
                        'meta' => ['admin_manual' => true],
                        'paid_at' => now(),
                    ]);
                } else {
                    $company->update(['package' => $paymentRequest->package_id]);
                }
                $paymentRequest->update([
                    'status' => PaymentRequest::STATUS_COMPLETED,
                    'gateway_transaction_id' => $result->getPaymentId(),
                    'completed_at' => now(),
                ]);
            });

            return ['success' => true, 'payment_request' => $paymentRequest->fresh()];
        } catch (\Throwable $e) {
            $paymentRequest->update(['status' => PaymentRequest::STATUS_FAILED]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
