<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- ApexCharts for beautiful charts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Moment.js for date formatting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- Custom JS -->
<script>
    // Define base URL for JavaScript use
    var baseUrl = '<?= base_url() ?>/';
</script>
<script src="<?= base_url('assets/js/script.js') ?>"></script>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });

        // Handle logout button in modal
        $('#btn-logout-modal').click(function() {
            $('#userModal').modal('hide');
            $('#logoutModal').modal('show');
        });
    });
</script>

<!-- Section for page-specific scripts -->
<?= $this->renderSection('scripts') ?>

<!-- Modal wrapper for handling modal backdrop correctly -->
<div id="modal-container"></div>