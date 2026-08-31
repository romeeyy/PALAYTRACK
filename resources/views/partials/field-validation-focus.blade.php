@if($errors->any() || $errors->passwordUpdate->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const firstInvalidField = document.querySelector('.is-invalid');

        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            window.setTimeout(() => firstInvalidField.focus({ preventScroll: true }), 350);
        }
    });
</script>
@endif
