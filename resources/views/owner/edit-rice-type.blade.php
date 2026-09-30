@extends('layouts.owner')

@section('content')

<style>
    .page-shell {
        max-width: 900px;
        margin: 0 auto;
    }

    .form-page-header { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:20px; }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #ffffff;
        color: #111827;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #111827;
    }

    .form-card {
        background: linear-gradient(135deg, #f9fdf9 0%, #eaf5ec 100%);
        border: none;
        border-radius: 20px;
        padding: 30px 30px 26px;
        border: 1px solid #b9d2bd;
        box-shadow: 0 14px 30px rgba(24, 65, 35, 0.10);
    }

    .form-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .form-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
    }

    .field-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
    }

    .custom-input,
    .custom-select,
    .custom-textarea {
        border: 1px solid #cbdccf;
        background: #f4faf5;
        border-radius: 14px;
        min-height: 52px;
        padding: 14px 16px;
        color: #111827;
        box-shadow: none;
    }

    .custom-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-textarea:focus {
        background: #ffffff;
        border-color: #2f5d1e;
        box-shadow: 0 0 0 0.18rem rgba(47, 93, 30, 0.12);
    }

    .help-text {
        font-size: 0.92rem;
        color: #64748b;
        margin-top: 8px;
        line-height: 1.5;
    }

    .preview-box {
        margin-top: 10px;
        background: linear-gradient(135deg, #effcf2 0%, #e2f7e8 100%);
        border: 2px solid #38b866;
        border-radius: 16px;
        padding: 18px 20px 16px;
    }

    .preview-label {
        font-size: 0.95rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .preview-value {
        font-size: 1.9rem;
        font-weight: 800;
        color: #15803d;
        line-height: 1.1;
    }

    .btn-main {
        min-height: 52px;
        border-radius: 12px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #2f5d1e 0%, #3f7a28 100%);
        border: none;
        color: #fff;
        font-weight: 700;
        box-shadow: 0 10px 18px rgba(47, 93, 30, 0.18);
        transition: all 0.2s ease;
    }

    .btn-main:hover {
        background: linear-gradient(135deg, #274d19 0%, #35671f 100%);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-cancel {
        min-height: 52px;
        border-radius: 12px;
        padding: 12px 20px;
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

    .error-text {
        font-size: 0.9rem;
        color: #dc2626;
        margin-top: 8px;
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

        .preview-value {
            font-size: 1.6rem;
        }
    }
</style>

<div class="page-shell">
    <div class="form-page-header">
        <div>
            <h1 class="form-title">Edit Rice Type</h1>
            <p class="form-subtitle">Update rice variety details and its assigned recovery rate.</p>
        </div>
        <a href="/owner/rice-types" class="back-btn">
            <i data-lucide="arrow-left"></i>
            <span>Back to Rice Types</span>
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="/owner/edit-rice-type/{{ $riceType->id }}">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="field-label">Rice Type / Variety Name *</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control custom-input"
                        value="{{ old('name', $riceType->name) }}"
                        required
                    >
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="field-label">Recovery Rate (%) *</label>
                    <input
                        id="recoveryRateInput"
                        type="number"
                        step="0.01"
                        min="0.01"
                        max="100"
                        name="recovery_rate"
                        class="form-control custom-input"
                        value="{{ old('recovery_rate', $riceType->recovery_rate) }}"
                        required
                    >
                    <div class="help-text">This rate will be used to estimate milled rice output.</div>
                    @error('recovery_rate')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

            

                <div class="col-md-6">
                    <label class="field-label">Status *</label>
                    <select name="status" class="form-select custom-select" required>
                        <option value="active" {{ old('status', $riceType->status) == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ old('status', $riceType->status) == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                    @error('status')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="field-label">Typical Milling Output Preview</label>
                    <div class="preview-box">
                        <div class="preview-label">Based on 100 kg of palay</div>
                        <div id="previewOutput" class="preview-value">0.00 kg</div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="field-label">Description</label>
                    <textarea
                        name="description"
                        class="form-control custom-textarea"
                        placeholder="Describe this rice type or variety"
                    >{{ old('description', $riceType->description) }}</textarea>
                    @error('description')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button type="submit" class="btn btn-main">
                    Update Rice Type
                </button>

                <a href="/owner/rice-types" class="btn btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const recoveryRateInput = document.getElementById('recoveryRateInput');
    const previewOutput = document.getElementById('previewOutput');

    function updatePreviewOutput() {
        const rate = parseFloat(recoveryRateInput.value) || 0;
        const result = 100 * (rate / 100);

        previewOutput.textContent = result.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' kg';
    }

    recoveryRateInput.addEventListener('input', updatePreviewOutput);
    updatePreviewOutput();
</script>

@endsection
