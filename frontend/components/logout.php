<button
    type="button"
    class="sidebar-logout"
    data-logout-open>
    <span class="sidebar-icon">↪</span>
    <span>Đăng xuất</span>
</button>

<div
    id="logoutModal"
    class="custom-modal-overlay"
    data-logout-modal
    style="display:none;"
    aria-hidden="true">

    <div class="custom-modal-card">

        <div class="custom-modal-header">
            <h3>Xác nhận đăng xuất</h3>
        </div>

        <div class="custom-modal-body">
            <p>
                Bạn có chắc chắn muốn thoát khỏi phiên làm việc hiện tại không?
            </p>
        </div>

        <div class="custom-modal-footer">

            <button
                type="button"
                class="btn-cancel"
                data-logout-cancel>
                Hủy
            </button>

            <button
                type="button"
                class="btn-confirm-logout"
                data-logout-confirm>
                Đăng xuất
            </button>

        </div>

    </div>

</div>

<script
    type="module"
    src="/dashboard/CTUMedic/frontend/assets/js/shared/logout.js"></script>
