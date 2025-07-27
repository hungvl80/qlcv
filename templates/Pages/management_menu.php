<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Quản lý Table Động');
?>

<div class="container py-5">
    <h1 class="text-center mb-4"><i class="bi bi-table"></i> Quản lý Table Động</h1>
    
    <div class="table-builder card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-tools"></i> Tạo/Chỉnh sửa Table</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tableName" class="form-label">Tên Table</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="tableName" placeholder="Nhập tên table (ví dụ: my_table)">
                        <button class="btn btn-primary" id="createTableBtn"><i class="bi bi-plus-circle"></i> Tạo mới</button>
                    </div>
                    <small class="text-muted">Tên table sẽ được chuyển thành dạng snake_case tự động</small>
                </div>
                <div class="col-md-6">
                    <label for="existingTables" class="form-label">Chọn Table hiện có</label>
                    <select class="form-select" id="existingTables">
                        <option value="">-- Chọn Table --</option>
                        <?php foreach ($existingTables as $table): ?>
                            <option value="<?= h($table) ?>"><?= h($table) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="dynamic-table card shadow-sm mb-4" id="tableStructureSection" style="display: none;">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0"><i class="bi bi-gear"></i> Cấu trúc Table: <span id="currentTableName"></span></h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Các cột trong Table</h5>
                <div>
                    <button class="btn btn-success me-2" id="addColumnBtn"><i class="bi bi-plus-circle"></i> Thêm Cột</button>
                    <button class="btn btn-primary" id="saveStructureBtn" title="Lưu lại cấu trúc table (Lưu ý: Chức năng này rất phức tạp và hiện là placeholder)."><i class="bi bi-save"></i> Lưu Cấu trúc</button>
                    <button class="btn btn-danger ms-2" id="deleteTableBtn"><i class="bi bi-trash"></i> Xóa Table</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tên Cột</th>
                            <th>Kiểu</th>
                            <th>Độ dài</th>
                            <th>Cho phép NULL</th>
                            <th>Mặc định</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="columnsTableBody">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="dynamic-table card shadow-sm" id="tableDataSection" style="display: none;">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0"><i class="bi bi-database"></i> Dữ liệu Table: <span id="dataTableName"></span></h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Tổng dòng: <span id="rowCount">0</span></h5>
                <button class="btn btn-info" id="addRowBtn"><i class="bi bi-plus-circle"></i> Thêm Dòng</button>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light" id="dataTableHeader">
                        </thead>
                    <tbody id="dataTableBody">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addColumnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="columnModalTitle">Thêm cột mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="columnForm">
                    <div class="mb-3" id="columnOldNameContainer" style="display: none;">
                        <label for="columnOldName" class="form-label">Tên Cột Cũ</label>
                        <input type="text" class="form-control" id="columnOldName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="columnName" class="form-label">Tên Cột Mới</label>
                        <input type="text" class="form-control" id="columnName" required placeholder="ví dụ: ten_cot_moi">
                    </div>
                    <div class="mb-3">
                        <label for="columnType" class="form-label">Kiểu Dữ liệu</label>
                        <select class="form-select" id="columnType" required>
                            <option value="string">String (Varchar)</option>
                            <option value="text">Text</option>
                            <option value="integer">Integer</option>
                            <option value="float">Float</option>
                            <option value="decimal">Decimal</option>
                            <option value="boolean">Boolean</option>
                            <option value="date">Date</option>
                            <option value="datetime">Datetime</option>
                        </select>
                    </div>
                    <div class="mb-3" id="lengthContainer">
                        <label for="columnLength" class="form-label">Độ dài (nếu có)</label>
                        <input type="number" class="form-control" id="columnLength" placeholder="ví dụ: 255">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="columnNullable">
                        <label class="form-check-label" for="columnNullable">Cho phép NULL</label>
                    </div>
                    <div class="mb-3">
                        <label for="columnDefault" class="form-label">Giá trị mặc định (nếu có)</label>
                        <input type="text" class="form-control" id="columnDefault" placeholder="ví dụ: 0 hoặc 'default_text'">
                    </div>
                    <div class="mb-3 form-check" id="isPrimaryContainer"> <input type="checkbox" class="form-check-input" id="columnIsPrimary">
                        <label class="form-check-label" for="columnIsPrimary">Là Khóa Chính?</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="saveColumnBtn">Lưu cột</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rowModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="rowModalTitle">Thêm dòng mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rowModalBody">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="saveRowBtn">Lưu dòng</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->css([
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
    // You might have a custom CSS file here if needed
]) ?>

<style>
    /* Custom styles for the dynamic table UI (can be moved to a .css file) */
    :root {
        --primary-color: #3a7bd5;
        --secondary-color: #00d2ff;
        --dark-color: #2c3e50;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }
    
    .table-builder, .dynamic-table {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .dynamic-table .card-header.bg-success {
        background-color: #28a745 !important; /* Green for structure */
    }
    .dynamic-table .card-header.bg-info {
        background-color: #17a2b8 !important; /* Blue for data */
    }

    .form-label {
        font-weight: 500;
    }
    .btn-primary, .btn-success, .btn-info {
        --bs-btn-bg: var(--primary-color);
        --bs-btn-border-color: var(--primary-color);
        --bs-btn-hover-bg: #2d60a5;
        --bs-btn-hover-border-color: #2d60a5;
    }
    .btn-success {
        --bs-btn-bg: #28a745;
        --bs-btn-border-color: #28a745;
        --bs-btn-hover-bg: #218838;
        --bs-btn-hover-border-color: #218838;
    }
    .btn-info {
        --bs-btn-bg: #17a2b8;
        --bs-btn-border-color: #17a2b8;
        --bs-btn-hover-bg: #138496;
        --bs-btn-hover-border-color: #138496;
    }
    .btn-danger {
        --bs-btn-bg: #dc3545;
        --bs-btn-border-color: #dc3545;
        --bs-btn-hover-bg: #c82333;
        --bs-btn-hover-border-color: #c82333;
    }
    .input-group > .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }
    .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
        border-radius: 0.25rem;
    }
    .modal-header .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
</style>

<?= $this->Html->script([
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    'dynamic_table.js', // Your custom JavaScript file
]) ?>