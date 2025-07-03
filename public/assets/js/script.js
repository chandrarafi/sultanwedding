// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl, {
        boundary: document.body
    });
});

// Mobile sidebar toggle
$(document).ready(function() {
    $('#sidebarToggle, #navbarToggler').on('click', function() {
        $('#sidebar').toggleClass('show');

        // Change icon based on sidebar state
        if ($('#sidebar').hasClass('show')) {
            $(this).find('i').removeClass('bi-list').addClass('bi-x');
        } else {
            $(this).find('i').removeClass('bi-x').addClass('bi-list');
        }
    });

    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() < 768) {
            if (!$(e.target).closest('#sidebar').length &&
                !$(e.target).closest('#sidebarToggle').length &&
                !$(e.target).closest('#navbarToggler').length &&
                $('#sidebar').hasClass('show')) {
                $('#sidebar').removeClass('show');
                $('#sidebarToggle, #navbarToggler').find('i').removeClass('bi-x').addClass('bi-list');
            }
        }
    });

    // Handle window resize
    $(window).resize(function() {
        if ($(window).width() >= 768) {
            $('#sidebar').removeClass('show');
            $('#sidebarToggle, #navbarToggler').find('i').removeClass('bi-x').addClass('bi-list');
        }
    });

    // Initialize DataTables if exists
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    }

    // Handle logout button
    $('#btn-logout').click(function() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8e44ad',
            cancelButtonColor: '#e74c3c',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseUrl + 'auth/logout',
                    type: 'GET',
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Anda telah berhasil keluar',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = baseUrl + 'auth';
                        });
                    }
                });
            }
        });
    });

    // Modal handling
    // Move all modals to the end of body to ensure they work correctly
    $('.modal').appendTo('#modal-container');

    // Fix modal backdrop handling
    $(document).on('show.bs.modal', '.modal', function() {
        const $modal = $(this);
        const modalZIndex = 1060;

        $modal.css('z-index', modalZIndex);

        // Make sure there's only one backdrop
        if ($('.modal-backdrop').length === 0) {
            $('<div class="modal-backdrop show"></div>')
                .css('z-index', modalZIndex - 5)
                .appendTo('body');
        }

        $('body').addClass('modal-open');
    });

    $(document).on('hidden.bs.modal', '.modal', function() {
        // Only remove backdrop and modal-open class if no modal is visible
        if ($('.modal:visible').length === 0) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }
    });
}); 