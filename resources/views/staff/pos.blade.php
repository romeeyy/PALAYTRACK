@extends('layouts.staff')

@section('content')
<style>
    .pos-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .pos-page-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
        line-height: 1.2;
    }

    .pos-page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .back-btn {
        min-height: 44px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 15px;
        font-weight: 700;
        color: #334155;
        background: #fff;
    }

    .pos-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        margin-bottom: 18px;
    }

    .card-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 6px;
    }

    .card-subtitle {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 16px;
    }

    .field-label {
        display: block;
        font-size: 0.92rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 7px;
    }

    .custom-input,
    .custom-select,
    .custom-textarea {
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        border-radius: 11px;
        min-height: 46px;
        padding: 11px 14px;
        color: #111827;
        box-shadow: none;
    }

    .custom-textarea {
        min-height: 72px;
        resize: vertical;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 11px 14px;
        height: 100%;
    }

    .info-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.5;
    }

    .pricing-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: 9px 14px;
        border-radius: 999px;
        font-size: 0.88rem;
        font-weight: 900;
        letter-spacing: 0.3px;
    }

    .pricing-badge.commercial {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .pricing-badge.menudo {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .summary-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
        color: #334155;
        font-size: 0.96rem;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-row strong {
        color: #0f172a;
        font-weight: 800;
    }

    .summary-total {
        font-size: 1.05rem;
        padding-top: 14px;
        margin-top: 4px;
    }

    .summary-total strong {
        color: #15803d;
    }

    .btn-main {
        background: linear-gradient(135deg, #2f5d1e 0%, #3f7a28 100%);
        color: #fff;
        border: none;
        border-radius: 14px;
        min-height: 46px;
        padding: 10px 22px;
        font-weight: 800;
        box-shadow: 0 8px 16px rgba(47, 93, 30, 0.16);
    }

    .btn-main:hover {
        background: linear-gradient(135deg, #274d19 0%, #35671f 100%);
        color: #fff;
    }

    .alert-custom {
        border: none;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 0.95rem;
        margin-bottom: 20px;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
    }

    .payment-submit {
        display: flex;
        justify-content: flex-end;
    }

    .payment-submit .btn-main {
        min-width: 190px;
    }

    .payment-summary-card {
        position: sticky;
        top: 18px;
    }

    @media (max-width: 768px) {
        .pos-page-title {
            font-size: 1.7rem;
        }

        .pos-card {
            padding: 18px;
        }

        .payment-summary-card {
            position: static;
        }

        .payment-submit .btn-main {
            width: 100%;
        }
    }
</style>
@include('partials.form-dark-mode')

<div class="pos-page-header">
    <div>
        <h1 class="pos-page-title">Billing &amp; Payment</h1>
        <p class="pos-page-subtitle">Record payment for this completed delivery.</p>
    </div>

    <a href="{{ url('/staff/delivery-details/' . $delivery->id) }}" class="btn btn-outline-secondary back-btn">
        &larr; Back to Delivery
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-custom">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-custom">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div
    id="pos-page-data"
    data-palay-weight="{{ (float) $palayWeight }}"
    data-menudo-fee="{{ (float) $menudoFee }}"
    data-commercial-fee="{{ (float) $commercialFee }}">
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="pos-card">
            <h2 class="card-title">Transaction Details</h2>
            <p class="card-subtitle">Enter payment information and charges for this delivery.</p>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Delivery ID</div>
                        <div class="info-value">{{ $delivery->delivery_id }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Client Name</div>
                        <div class="info-value">{{ $delivery->client_name }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Rice Type</div>
                        <div class="info-value">{{ $delivery->riceType->name ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-box">
                        <div class="info-label">Palay Weight</div>
                        <div class="info-value">{{ number_format($palayWeight, 2) }} kg</div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('staff.pos.store', $delivery->id) }}"
                data-confirm-title="Confirm Payment"
                data-confirm-message="Confirm and save this payment? Please verify the amount and payment method before continuing."
                data-confirm-button="Save Payment">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">System-Applied Pricing</div>

                            <div class="mt-2">
                                <span class="pricing-badge {{ $millingType === 'commercial' ? 'commercial' : 'menudo' }}">
                                    {{ $millingType === 'commercial' ? 'Commercial' : 'Menudo' }}
                                </span>
                            </div>

                            <div class="small text-muted mt-2">
                                Based on client classification and milling weight.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="field-label" for="milling_fee_per_kg">Milling Fee per Kg</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="milling_fee_per_kg"
                            id="milling_fee_per_kg"
                            class="form-control custom-input"
                            value="{{ old('milling_fee_per_kg', $millingFeePerKg) }}"
                            required
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="field-label" for="other_charges">Other Charges</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="other_charges"
                            id="other_charges"
                            class="form-control custom-input"
                            value="{{ old('other_charges') }}"
                            placeholder="0.00">
                    </div>

                    <div class="col-md-6">
                        <label class="field-label" for="discount">Discount</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="discount"
                            id="discount"
                            class="form-control custom-input"
                            value="{{ old('discount') }}"
                            placeholder="0.00">
                    </div>

                    <div class="col-md-6">
                        <label class="field-label" for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" class="form-select custom-select" required>
                            <option value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="maya" {{ old('payment_method') == 'maya' ? 'selected' : '' }}>Maya</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="field-label" for="amount_received" id="amount_received_label">Amount Received</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="amount_received"
                            id="amount_received"
                            class="form-control custom-input"
                            value="{{ old('amount_received') }}"
                            placeholder="0.00"
                            required>
                    </div>

                    <div class="col-md-6" id="reference_number_wrapper">
                        <label class="field-label" for="reference_number">Reference Number</label>
                        <input
                            type="text"
                            name="reference_number"
                            id="reference_number"
                            class="form-control custom-input"
                            value="{{ old('reference_number') }}"
                            placeholder="Enter reference number">
                    </div>

                    <div class="col-12">
                        <label class="field-label" for="notes">Notes / Adjustment Reason</label>
                        <textarea
                            name="notes"
                            id="notes"
                            class="form-control custom-textarea"
                            placeholder="Required when adding other charges or a discount">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 pt-2 payment-submit">
                        <button type="submit" class="btn btn-main">
                            Save Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="pos-card payment-summary-card">
            <h2 class="card-title">Payment Summary</h2>
            <p class="card-subtitle">Review the computed charges before saving payment.</p>

            <div class="summary-box">
                <div class="summary-row">
                    <span>Palay Weight</span>
                    <strong>{{ number_format($palayWeight, 2) }} kg</strong>
                </div>

                <div class="summary-row">
                    <span>Fee per Kg</span>
                    <strong>₱ <span id="display_fee">0.00</span></strong>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>₱ <span id="display_subtotal">0.00</span></strong>
                </div>

                <div class="summary-row">
                    <span>Other Charges</span>
                    <strong>₱ <span id="display_other">0.00</span></strong>
                </div>

                <div class="summary-row">
                    <span>Discount</span>
                    <strong>₱ <span id="display_discount">0.00</span></strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Total Amount</span>
                    <strong>₱ <span id="display_total">0.00</span></strong>
                </div>

                <div class="summary-row">
                    <span>Amount Received</span>
                    <strong>₱ <span id="display_received">0.00</span></strong>
                </div>

                <div class="summary-row">
                    <span>Change</span>
                    <strong>₱ <span id="display_change">0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const posPageData = document.getElementById('pos-page-data');
    const palayWeight = parseFloat(posPageData.dataset.palayWeight || 0);

    const feeInput = document.getElementById('milling_fee_per_kg');
    const otherInput = document.getElementById('other_charges');
    const discountInput = document.getElementById('discount');
    const receivedInput = document.getElementById('amount_received');
    const paymentMethodInput = document.getElementById('payment_method');
    const referenceWrapper = document.getElementById('reference_number_wrapper');
    const referenceInput = document.getElementById('reference_number');
    const receivedLabel = document.getElementById('amount_received_label');

    function money(value) {
        return Number(value || 0).toFixed(2);
    }

    function updateSummary() {
        const fee = parseFloat(feeInput?.value) || 0;
        const other = parseFloat(otherInput?.value) || 0;
        const discount = parseFloat(discountInput?.value) || 0;
        const received = parseFloat(receivedInput?.value) || 0;

        const subtotal = palayWeight * fee;
        const total = Math.max((subtotal + other) - discount, 0);
        const change = Math.max(received - total, 0);

        document.getElementById('display_fee').textContent = money(fee);
        document.getElementById('display_subtotal').textContent = money(subtotal);
        document.getElementById('display_other').textContent = money(other);
        document.getElementById('display_discount').textContent = money(discount);
        document.getElementById('display_total').textContent = money(total);
        document.getElementById('display_received').textContent = money(received);
        document.getElementById('display_change').textContent = money(change);
    }

    function toggleReferenceField() {
        if (!paymentMethodInput || !referenceWrapper) return;

        const method = paymentMethodInput.value;
        const isCash = method === 'cash';

        if (receivedLabel) {
            receivedLabel.textContent = isCash ? 'Amount Received' : 'Exact Digital Amount';
        }

        referenceWrapper.style.display = isCash ? 'none' : 'block';

        if (referenceInput) {
            referenceInput.required = !isCash;

            if (isCash) {
                referenceInput.value = '';
                referenceInput.removeAttribute('maxlength');
                referenceInput.removeAttribute('minlength');
                referenceInput.removeAttribute('pattern');
                referenceInput.placeholder = '';
            }

            if (method === 'gcash') {
                referenceInput.setAttribute('maxlength', '13');
                referenceInput.setAttribute('minlength', '13');
                referenceInput.setAttribute('pattern', '\\d{13}');
                referenceInput.placeholder = 'Enter 13-digit GCash reference';
            }

            if (method === 'maya') {
                referenceInput.setAttribute('maxlength', '20');
                referenceInput.setAttribute('minlength', '6');
                referenceInput.setAttribute('pattern', '\\d{6,20}');
                referenceInput.placeholder = 'Enter 6–20 digit Maya reference';
            }
        }

        if (!isCash && receivedInput) {
            const fee = parseFloat(feeInput?.value) || 0;
            const other = parseFloat(otherInput?.value) || 0;
            const discount = parseFloat(discountInput?.value) || 0;
            receivedInput.value = money(Math.max((palayWeight * fee + other) - discount, 0));
        }

        updateSummary();
    }

    function updateAmounts() {
        if (paymentMethodInput?.value !== 'cash' && receivedInput) {
            const fee = parseFloat(feeInput?.value) || 0;
            const other = parseFloat(otherInput?.value) || 0;
            const discount = parseFloat(discountInput?.value) || 0;
            receivedInput.value = money(Math.max((palayWeight * fee + other) - discount, 0));
        }
        updateSummary();
    }

    if (otherInput) otherInput.addEventListener('input', updateAmounts);
    if (discountInput) discountInput.addEventListener('input', updateAmounts);
    if (receivedInput) receivedInput.addEventListener('input', updateSummary);

    if (paymentMethodInput) {
        paymentMethodInput.addEventListener('change', toggleReferenceField);
    }

    updateSummary();
    toggleReferenceField();
</script>
@endsection
