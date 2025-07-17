<!-- jQuery first, then Bootstrap JS -->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!--plugins-->
<script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
<!-- Moment.js untuk format tanggal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="<?= base_url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') ?>"></script>
<script src="<?= base_url('assets/plugins/chartjs/js/chart.js') ?>"></script>
<!-- DataTables -->
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
<!-- DataTables Responsive Extension -->
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--app JS-->
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<script>
    // Define base URL for JavaScript use
    var baseUrl = '<?= base_url() ?>/';

    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });

        // Initialize dropdowns
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });

        // Specifically initialize user dropdown
        const userDropdownToggle = document.querySelector('.user-box .dropdown-toggle');
        if (userDropdownToggle) {
            const userDropdown = new bootstrap.Dropdown(userDropdownToggle);

            // Add click handler to ensure dropdown opens
            userDropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                userDropdown.toggle();
            });
        }

        // Handle logout button in modal
        $('#btn-logout-modal').click(function() {
            $('#userModal').modal('hide');
            $('#logoutModal').modal('show');
        });

        // Inisialisasi PerfectScrollbar hanya jika elemen ada
        const appContainer = document.querySelector(".app-container");
        if (appContainer) {
            new PerfectScrollbar(appContainer);
        }
    });
</script>

<!-- Section for page-specific scripts -->
<?= $this->renderSection('scripts') ?>

<!-- Modal wrapper for handling modal backdrop correctly -->
<div id="modal-container"></div>