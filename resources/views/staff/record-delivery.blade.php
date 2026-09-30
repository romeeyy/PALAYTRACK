@extends('layouts.staff')

@section('content')

<style>
    .page-shell {
        max-width: 1100px;
        margin: 0 auto;
    }

    .form-page-header { display:flex; align-items:flex-start; justify-content:space-between; gap:14px; margin-bottom:12px; }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 40px;
        padding: 8px 13px;
        border: 1px solid color-mix(in srgb, var(--user-accent, #168344) 28%, #d1d5db);
        border-radius: 10px;
        background: #ffffff;
        color: var(--user-accent-dark, #166534);
        text-decoration: none;
        font-size: .88rem;
        font-weight: 800;
        transition: all 0.2s ease;
    }

    .back-btn:hover {
        background: var(--user-accent-soft, #f8fafc);
        border-color: var(--user-accent, #168344);
        color: var(--user-accent-dark, #166534);
    }

    .form-card {
        background: linear-gradient(135deg, #ffffff 0%, #f4faf5 100%);
        border: 1px solid #d5e5d8;
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 14px 30px rgba(24, 65, 35, 0.09);
    }

    .form-card .row { --bs-gutter-y: .75rem; }
    .form-card .mt-4 { margin-top: 1rem !important; }

    .form-title {
        font-size: 1.7rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .form-subtitle {
        color: #64748b;
        font-size: .95rem;
        margin: 0;
    }

    .field-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .required-mark { color: var(--user-accent-dark, #166534); font-weight: 900; margin-left: 2px; }

    .custom-input,
    .custom-select,
    .custom-textarea {
        border: 1px solid #d5e1d8;
        background: #f7faf8;
        border-radius: 11px;
        min-height: 44px;
        padding: 10px 13px;
        color: #111827;
        box-shadow: none;
    }

    .custom-textarea {
        min-height: 64px;
        resize: none;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .help-text {
        font-size: 0.82rem;
        color: #64748b;
        margin-top: 4px;
        line-height: 1.35;
    }

    .estimate-box {
        margin-top: 4px;
        background: linear-gradient(135deg, #effcf2 0%, #e2f7e8 100%);
        border: 1px solid #38b866;
        border-radius: 12px;
        padding: 12px 14px;
        display: grid;
        grid-template-columns: auto auto auto minmax(240px, 1fr);
        align-items: center;
        gap: 14px;
    }

    .estimate-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 0;
    }

    .estimate-icon { width: 30px; height: 30px; display: grid; place-items: center; border-radius: 9px; background: var(--user-accent-soft, #eaf7ef); color: var(--user-accent-dark, #166534); }
    .estimate-icon svg { width: 17px; height: 17px; }

    .estimate-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #15803d;
        line-height: 1;
        margin-bottom: 0;
    }

    .estimate-note {
        color: #64748b;
        font-size: 0.84rem;
        line-height: 1.35;
    }

    .btn-main {
        min-height: 44px;
        border-radius: 10px;
        padding: 9px 16px;
        background: var(--user-accent, #168344);
        border: none;
        color: #fff;
        font-weight: 700;
        box-shadow: 0 10px 18px var(--user-accent-ring, rgba(47, 93, 30, 0.18));
        transition: all 0.2s ease;
    }

    .btn-main:hover {
        background: var(--user-accent-dark, #125f34);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-cancel {
        min-height: 44px;
        border-radius: 10px;
        padding: 9px 16px;
        background: #ffffff;
        border: 1px solid #d1d5db;
        color: #111827;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #111827;
    }

    .custom-input.is-invalid,
    .custom-input.is-invalid:focus {
        border-color: #ef4444;
        background-color: #fffafa;
        box-shadow: 0 0 0 0.16rem rgba(239, 68, 68, 0.08);
    }

    .invalid-feedback {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
        color: #b42318;
        font-size: 0.8rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .invalid-feedback::before {
        content: "!";
        display: inline-grid;
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        place-items: center;
        border-radius: 50%;
        background: #fee4e2;
        color: #b42318;
        font-size: 0.68rem;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .form-page-header { align-items:stretch; flex-direction:column; }
        .back-btn { align-self:flex-start; }

        .form-card {
            padding: 22px 18px;
        }

        .form-title {
            font-size: 1.75rem;
        }

        .estimate-value {
            font-size: 2rem;
        }

        .estimate-box {
            grid-template-columns: 1fr;
            gap: 5px;
        }
    }
</style>
@include('partials.form-dark-mode')

<div class="page-shell">
    <div class="form-page-header">
        <div>
            <h1 class="form-title">Record Palay Delivery</h1>
            <p class="form-subtitle">Enter the client and palay delivery information below.</p>
        </div>
        <a href="/staff/deliveries" class="back-btn">
            <i data-lucide="arrow-left"></i>
            <span>Back to Deliveries</span>
        </a>
    </div>

    <div class="form-card">
       <form id="deliveryForm" action="{{ route('staff.record-delivery.store') }}" method="POST">
    @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="field-label">Client / Palay Owner Name <span class="required-mark" aria-hidden="true">*</span></label>
                    <input
                        type="text"
                        name="client_name"
                        value="{{ old('client_name') }}"
                        class="form-control custom-input text-capitalize-input @error('client_name') is-invalid @enderror"
                        placeholder="Enter client name"
                        required
                    >
                    @error('client_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="field-label">Contact Number <span class="required-mark" aria-hidden="true">*</span></label>
                    <input
                        type="text"
                        name="contact_number"
                        value="{{ old('contact_number') }}"
                        inputmode="tel"
                        autocomplete="tel"
                        class="form-control custom-input @error('contact_number') is-invalid @enderror"
                        placeholder="09XX-XXX-XXXX"
                        required
                    >
                    @error('contact_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="field-label">Rice Type / Variety <span class="required-mark" aria-hidden="true">*</span></label>
                    <select id="riceType" name="rice_type_id" class="form-select custom-select" required>
                        <option value="" selected disabled>Select rice type</option>
                        @foreach($riceTypes as $riceType)
                            <option value="{{ $riceType->id }}" data-rate="{{ $riceType->recovery_rate }}">
                                {{ $riceType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Number of Sacks <span class="required-mark" aria-hidden="true">*</span></label>
                    <input
                        type="number"
                        name="sacks"
                        class="form-control custom-input"
                        placeholder="Enter number of sacks"
                        min="0.5"
                        step="0.5"
                        required
                    >
                    <div class="help-text">Whole or half sacks only (example: 2 or 2.5).</div>
                </div>

                <div class="col-md-6">
                    <label class="field-label">Total Weight of Palay (kg) <span class="required-mark" aria-hidden="true">*</span></label>
                    <input
                        id="palayWeight"
                        type="number"
                        step="0.01"
                        min="1"
                        name="palay_weight"
                        value="{{ old('palay_weight') }}"
                        class="form-control custom-input @error('palay_weight') is-invalid @enderror"
                        placeholder="Enter weight in kg"
                        required
                    >
                    @error('palay_weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="field-label">Recovery Rate (%) <span class="required-mark" aria-hidden="true">*</span></label>
                    <input
                        id="recoveryRate"
                        type="number"
                        step="0.01"
                        name="recovery_rate_preview"
                        class="form-control custom-input"
                        placeholder="Auto-filled recovery rate"
                        readonly
                    >
                    <div class="help-text">Recovery rate is automatically assigned based on the selected rice type.</div>
                </div>

                <div class="col-12">
                    <div class="estimate-box">
                        <div class="estimate-icon" aria-hidden="true"><i data-lucide="calculator"></i></div>
                        <div class="estimate-label">Estimated Output</div>
                        <div id="estimatedRice" class="estimate-value">0.00 kg</div>
                        <div class="estimate-note">Automatically calculated based on palay weight and recovery rate.</div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="field-label">Notes</label>
                    <textarea
                        name="notes"
                        class="form-control custom-textarea"
                        placeholder="Additional notes (optional)"
                    ></textarea>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <a href="/staff/deliveries" class="btn btn-cancel">
                    Cancel
                </a>

                <button type="submit" class="btn btn-main">
                    Submit & Generate Claim Stub
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const deliveryForm = document.getElementById('deliveryForm');
    const riceType = document.getElementById('riceType');
    const palayWeight = document.getElementById('palayWeight');
    const recoveryRate = document.getElementById('recoveryRate');
    const estimatedRice = document.getElementById('estimatedRice');
    const errorBox = document.getElementById('errorBox');

    function calculateEstimatedRice() {
        const weight = parseFloat(palayWeight.value) || 0;
        const rate = parseFloat(recoveryRate.value) || 0;
        const result = weight * (rate / 100);

        estimatedRice.textContent = result.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' kg';
    }

    riceType.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const rate = selectedOption.getAttribute('data-rate') || 0;
        recoveryRate.value = rate;
        calculateEstimatedRice();
    });

    palayWeight.addEventListener('input', calculateEstimatedRice);

    document.addEventListener("DOMContentLoaded", function () {
    const capitalizeInputs = document.querySelectorAll('.text-capitalize-input');

    capitalizeInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            let words = input.value.toLowerCase().split(' ');

            words = words.map(function (word) {
                if (word.length === 0) {
                    return word;
                }

                return word.charAt(0).toUpperCase() + word.slice(1);
            });

            input.value = words.join(' ');
        });
    });
});
</script>


@endsection
