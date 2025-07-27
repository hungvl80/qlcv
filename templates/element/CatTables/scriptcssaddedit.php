<?php $this->append('script'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('cat-table-form');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
<?php $this->end(); ?>

<?php $this->append('css'); ?>
<style>
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .form-floating > label {
        padding: 1rem 1.25rem;
    }

    .form-control-lg, .form-select-lg {
        height: calc(3.5rem + 2px);
        padding: 1rem 1.25rem;
        border-radius: 0.5rem;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }
</style>
<?php $this->end(); ?>
