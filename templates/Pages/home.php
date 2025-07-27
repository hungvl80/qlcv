<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Table Động</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #3a7bd5;
            --secondary-color: #00d2ff;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .table-builder {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .dynamic-table {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .column-type-badge {
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .table-name-display {
            font-weight: 600;
            color: var(--primary-color);
            background-color: rgba(58, 123, 213, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            display: inline-block;
        }
        
        /* CSS mới bổ sung */
        .sort-column-btn {
            padding: 0.1rem 0.3rem;
            font-size: 0.7rem;
            margin: 0 1px;
            cursor: pointer;
        }
        
        .sort-column-btn:hover {
            background-color: #e9ecef;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .move-up, .move-down {
            color: #6c757d;
        }
        
        .move-up:hover, .move-down:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="text-center mb-4"><i class="bi bi-table"></i> Quản lý Table Động</h1>
        
        <!-- Table Builder Section -->
        <div class="table-builder">
            <h3 class="mb-4"><i class="bi bi-tools"></i> Tạo/Chỉnh sửa Table</h3>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tableName" class="form-label">Tên Table</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="tableName" placeholder="Nhập tên table">
                        <button class="btn btn-primary" id="createTableBtn"><i class="bi bi-plus-circle"></i> Tạo mới</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="existingTables" class="form-label">Chọn Table hiện có</label>
                    <select class="form-select" id="existingTables">
                        <option value="">-- Chọn table --</option>
                        <option value="users">Users</option>
                        <option value="products">Products</option>
                    </select>
                </div>
            </div>
            
            <div id="tableStructureSection" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Cấu trúc Table: <span id="currentTableName" class="table-name-display"></span></h5>
                    <button class="btn btn-sm btn-success" id="addColumnBtn"><i class="bi bi-plus"></i> Thêm cột</button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="columnsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Tên cột</th>
                                <th>Kiểu dữ liệu</th>
                                <th>Độ dài/Values</th>
                                <th>Cho phép NULL</th>
                                <th>Khóa chính</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="columnsTableBody">
                            <!-- Columns will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-primary me-2" id="saveStructureBtn"><i class="bi bi-save"></i> Lưu cấu trúc</button>
                    <button class="btn btn-danger" id="deleteTableBtn"><i class="bi bi-trash"></i> Xóa table</button>
                </div>
            </div>
        </div>
        
        <!-- Data Management Section -->
        <div class="dynamic-table">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <h4 class="mb-0">Dữ liệu Table: <span id="dataTableName" class="table-name-display">Chưa chọn table</span></h4>
                <button class="btn btn-sm btn-primary" id="addRowBtn" disabled><i class="bi bi-plus"></i> Thêm dòng</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0" id="dataTable">
                    <thead id="dataTableHeader">
                        <tr>
                            <th colspan="10" class="text-center text-muted py-5">Vui lòng tạo hoặc chọn một table để xem dữ liệu</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        <!-- Data rows will be added here dynamically -->
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small">Hiển thị <span id="rowCount">0</span> dòng</div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Trước</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Add Column Modal -->
    <div class="modal fade" id="addColumnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm cột mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="columnForm">
                        <div class="mb-3">
                            <label for="columnName" class="form-label">Tên cột</label>
                            <input type="text" class="form-control" id="columnName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="columnType" class="form-label">Kiểu dữ liệu</label>
                            <select class="form-select" id="columnType" required>
                                <option value="varchar">VARCHAR (Chuỗi ký tự)</option>
                                <option value="int">INT (Số nguyên)</option>
                                <option value="decimal">DECIMAL (Số thập phân)</option>
                                <option value="date">DATE (Ngày tháng)</option>
                                <option value="datetime">DATETIME (Ngày giờ)</option>
                                <option value="text">TEXT (Văn bản dài)</option>
                                <option value="boolean">BOOLEAN (True/False)</option>
                                <option value="enum">ENUM (Giá trị cố định)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="lengthContainer" style="display: none;">
                            <label for="columnLength" class="form-label">Độ dài/Giá trị</label>
                            <input type="text" class="form-control" id="columnLength" placeholder="Ví dụ: 255 hoặc 'active,inactive' cho ENUM">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="allowNull">
                            <label class="form-check-label" for="allowNull">Cho phép NULL</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="isPrimary">
                            <label class="form-check-label" for="isPrimary">Khóa chính</label>
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
    
    <!-- Add/Edit Row Modal -->
    <div class="modal fade" id="rowModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rowModalTitle">Thêm dòng mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="rowModalBody">
                    <!-- Form fields will be added dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveRowBtn">Lưu dòng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Database simulation
        const database = {
            tables: {
                users: {
                    name: 'users',
                    columns: [
                        { name: 'id', type: 'int', length: null, allowNull: false, isPrimary: true },
                        { name: 'username', type: 'varchar', length: '50', allowNull: false },
                        { name: 'email', type: 'varchar', length: '100', allowNull: false },
                        { name: 'status', type: 'enum', length: "'active','inactive','banned'", allowNull: false },
                        { name: 'created_at', type: 'datetime', length: null, allowNull: false }
                    ],
                    data: [
                        { id: 1, username: 'admin', email: 'admin@example.com', status: 'active', created_at: '2023-01-01 10:00:00' },
                        { id: 2, username: 'user1', email: 'user1@example.com', status: 'active', created_at: '2023-01-02 11:30:00' }
                    ]
                },
                products: {
                    name: 'products',
                    columns: [
                        { name: 'id', type: 'int', length: null, allowNull: false, isPrimary: true },
                        { name: 'name', type: 'varchar', length: '100', allowNull: false },
                        { name: 'price', type: 'decimal', length: '10,2', allowNull: false },
                        { name: 'quantity', type: 'int', length: null, allowNull: false },
                        { name: 'is_active', type: 'boolean', length: null, allowNull: false }
                    ],
                    data: [
                        { id: 1, name: 'Product A', price: 19.99, quantity: 100, is_active: true },
                        { id: 2, name: 'Product B', price: 29.99, quantity: 50, is_active: true }
                    ]
                }
            },
            currentTable: null
        };

        // DOM Elements
        const tableNameInput = document.getElementById('tableName');
        const createTableBtn = document.getElementById('createTableBtn');
        const existingTablesSelect = document.getElementById('existingTables');
        const tableStructureSection = document.getElementById('tableStructureSection');
        const currentTableNameSpan = document.getElementById('currentTableName');
        const columnsTableBody = document.getElementById('columnsTableBody');
        const addColumnBtn = document.getElementById('addColumnBtn');
        const saveStructureBtn = document.getElementById('saveStructureBtn');
        const deleteTableBtn = document.getElementById('deleteTableBtn');
        const dataTableNameSpan = document.getElementById('dataTableName');
        const dataTableHeader = document.getElementById('dataTableHeader');
        const dataTableBody = document.getElementById('dataTableBody');
        const addRowBtn = document.getElementById('addRowBtn');
        const rowCountSpan = document.getElementById('rowCount');
        
        // Modal elements
        const addColumnModal = new bootstrap.Modal(document.getElementById('addColumnModal'));
        const columnForm = document.getElementById('columnForm');
        const columnTypeSelect = document.getElementById('columnType');
        const lengthContainer = document.getElementById('lengthContainer');
        const saveColumnBtn = document.getElementById('saveColumnBtn');
        
        const rowModal = new bootstrap.Modal(document.getElementById('rowModal'));
        const rowModalTitle = document.getElementById('rowModalTitle');
        const rowModalBody = document.getElementById('rowModalBody');
        const saveRowBtn = document.getElementById('saveRowBtn');
        
        // Event Listeners
        createTableBtn.addEventListener('click', createTable);
        existingTablesSelect.addEventListener('change', loadTable);
        addColumnBtn.addEventListener('click', showAddColumnModal);
        columnTypeSelect.addEventListener('change', toggleLengthField);
        saveColumnBtn.addEventListener('click', saveColumn);
        saveStructureBtn.addEventListener('click', saveTableStructure);
        deleteTableBtn.addEventListener('click', deleteTable);
        addRowBtn.addEventListener('click', showAddRowModal);
        
        // Functions
        function createTable() {
            const tableName = tableNameInput.value.trim();
            if (!tableName) {
                alert('Vui lòng nhập tên table');
                return;
            }
            
            if (database.tables[tableName]) {
                alert('Table đã tồn tại');
                return;
            }
            
            // Create new table
            database.tables[tableName] = {
                name: tableName,
                columns: [],
                data: []
            };
            
            database.currentTable = tableName;
            updateUI();
            tableNameInput.value = '';
            existingTablesSelect.value = tableName;
        }
        
        function loadTable() {
            const tableName = existingTablesSelect.value;
            if (!tableName) return;
            
            database.currentTable = tableName;
            updateUI();
        }
        
        function updateUI() {
            const tableName = database.currentTable;
            const table = database.tables[tableName];
            
            // Update table structure section
            if (table) {
                tableStructureSection.style.display = 'block';
                currentTableNameSpan.textContent = tableName;
                dataTableNameSpan.textContent = tableName;
                addRowBtn.disabled = false;
                
                // Render columns table
                renderColumnsTable();
                
                // Render data table
                renderDataTable();
            } else {
                tableStructureSection.style.display = 'none';
                dataTableNameSpan.textContent = 'Chưa chọn table';
                addRowBtn.disabled = true;
            }
        }
        
        function renderColumnsTable() {
            const table = database.tables[database.currentTable];
            columnsTableBody.innerHTML = '';
            
            table.columns.forEach((column, index) => {
                const row = document.createElement('tr');
                row.setAttribute('data-index', index);
                
                row.innerHTML = `
                    <td>${column.name}</td>
                    <td>
                        <span class="badge bg-secondary column-type-badge">${column.type.toUpperCase()}</span>
                        ${column.length ? `(${column.length})` : ''}
                    </td>
                    <td>${column.length || '-'}</td>
                    <td>${column.allowNull ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>'}</td>
                    <td>${column.isPrimary ? '<i class="bi bi-key-fill text-warning"></i>' : '-'}</td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary edit-column" data-index="${index}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger delete-column" data-index="${index}"><i class="bi bi-trash"></i></button>
                        <button class="btn btn-sm sort-column-btn move-up" data-index="${index}" ${index === 0 ? 'disabled' : ''}>
                            <i class="bi bi-arrow-up"></i>
                        </button>
                        <button class="btn btn-sm sort-column-btn move-down" data-index="${index}" ${index === table.columns.length - 1 ? 'disabled' : ''}>
                            <i class="bi bi-arrow-down"></i>
                        </button>
                    </td>
                `;
                
                columnsTableBody.appendChild(row);
            });
            
            // Add event listeners to edit/delete buttons
            document.querySelectorAll('.edit-column').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    editColumn(index);
                });
            });
            
            document.querySelectorAll('.delete-column').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    deleteColumn(index);
                });
            });
            
            // Add event listeners to move buttons
            document.querySelectorAll('.move-up').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    moveColumnUp(index);
                });
            });
            
            document.querySelectorAll('.move-down').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    moveColumnDown(index);
                });
            });
        }
        
        function renderDataTable() {
            const table = database.tables[database.currentTable];
            
            if (!table.columns.length) {
                dataTableHeader.innerHTML = '<tr><th colspan="10" class="text-center text-muted py-5">Table chưa có cột nào</th></tr>';
                dataTableBody.innerHTML = '';
                rowCountSpan.textContent = '0';
                return;
            }
            
            // Render header
            let headerHTML = '<tr>';
            table.columns.forEach(column => {
                headerHTML += `<th>${column.name} <small class="text-muted">${column.type}</small></th>`;
            });
            headerHTML += '<th>Hành động</th></tr>';
            dataTableHeader.innerHTML = headerHTML;
            
            // Render body
            dataTableBody.innerHTML = '';
            table.data.forEach((row, rowIndex) => {
                const tr = document.createElement('tr');
                
                table.columns.forEach(column => {
                    const td = document.createElement('td');
                    td.textContent = row[column.name] !== undefined ? row[column.name] : 'NULL';
                    tr.appendChild(td);
                });
                
                // Add action buttons
                const actionTd = document.createElement('td');
                actionTd.className = 'action-buttons';
                actionTd.innerHTML = `
                    <button class="btn btn-sm btn-outline-primary edit-row" data-index="${rowIndex}"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger delete-row" data-index="${rowIndex}"><i class="bi bi-trash"></i></button>
                `;
                tr.appendChild(actionTd);
                
                dataTableBody.appendChild(tr);
            });
            
            // Update row count
            rowCountSpan.textContent = table.data.length;
            
            // Add event listeners to edit/delete buttons
            document.querySelectorAll('.edit-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    showEditRowModal(index);
                });
            });
            
            document.querySelectorAll('.delete-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    deleteRow(index);
                });
            });
        }
        
        function moveColumnUp(index) {
            if (index <= 0) return;
            
            const table = database.tables[database.currentTable];
            const temp = table.columns[index - 1];
            table.columns[index - 1] = table.columns[index];
            table.columns[index] = temp;
            
            renderColumnsTable();
            renderDataTable();
        }
        
        function moveColumnDown(index) {
            const table = database.tables[database.currentTable];
            if (index >= table.columns.length - 1) return;
            
            const temp = table.columns[index + 1];
            table.columns[index + 1] = table.columns[index];
            table.columns[index] = temp;
            
            renderColumnsTable();
            renderDataTable();
        }
        
        function showAddColumnModal() {
            columnForm.reset();
            lengthContainer.style.display = 'none';
            addColumnModal.show();
        }
        
        function toggleLengthField() {
            const type = columnTypeSelect.value;
            lengthContainer.style.display = (type === 'varchar' || type === 'decimal' || type === 'enum') ? 'block' : 'none';
        }
        
        function saveColumn() {
            const columnName = document.getElementById('columnName').value.trim();
            const columnType = columnTypeSelect.value;
            const columnLength = document.getElementById('columnLength').value.trim();
            const allowNull = document.getElementById('allowNull').checked;
            const isPrimary = document.getElementById('isPrimary').checked;
            
            if (!columnName) {
                alert('Vui lòng nhập tên cột');
                return;
            }
            
            // Add column to current table
            const table = database.tables[database.currentTable];
            table.columns.push({
                name: columnName,
                type: columnType,
                length: columnLength || null,
                allowNull,
                isPrimary
            });
            
            addColumnModal.hide();
            renderColumnsTable();
        }
        
        function editColumn(index) {
            const table = database.tables[database.currentTable];
            const column = table.columns[index];
            
            document.getElementById('columnName').value = column.name;
            document.getElementById('columnType').value = column.type;
            document.getElementById('columnLength').value = column.length || '';
            document.getElementById('allowNull').checked = column.allowNull;
            document.getElementById('isPrimary').checked = column.isPrimary;
            
            toggleLengthField();
            addColumnModal.show();
            
            // Change save button to update
            saveColumnBtn.textContent = 'Cập nhật';
            saveColumnBtn.onclick = function() {
                updateColumn(index);
            };
        }
        
        function updateColumn(index) {
            const columnName = document.getElementById('columnName').value.trim();
            const columnType = columnTypeSelect.value;
            const columnLength = document.getElementById('columnLength').value.trim();
            const allowNull = document.getElementById('allowNull').checked;
            const isPrimary = document.getElementById('isPrimary').checked;
            
            if (!columnName) {
                alert('Vui lòng nhập tên cột');
                return;
            }
            
            // Update column
            const table = database.tables[database.currentTable];
            table.columns[index] = {
                name: columnName,
                type: columnType,
                length: columnLength || null,
                allowNull,
                isPrimary
            };
            
            addColumnModal.hide();
            renderColumnsTable();
            renderDataTable();
            
            // Reset save button
            saveColumnBtn.textContent = 'Lưu cột';
            saveColumnBtn.onclick = saveColumn;
        }
        
        function deleteColumn(index) {
            if (!confirm('Bạn có chắc muốn xóa cột này?')) return;
            
            const table = database.tables[database.currentTable];
            table.columns.splice(index, 1);
            renderColumnsTable();
            renderDataTable();
        }
        
        function saveTableStructure() {
            alert('Cấu trúc table đã được lưu thành công!');
            renderDataTable();
        }
        
        function deleteTable() {
            const tableName = database.currentTable;
            if (!confirm(`Bạn có chắc muốn xóa table "${tableName}"?`)) return;
            
            delete database.tables[tableName];
            database.currentTable = null;
            existingTablesSelect.value = '';
            updateUI();
        }
        
        function showAddRowModal() {
            const table = database.tables[database.currentTable];
            rowModalTitle.textContent = `Thêm dòng mới vào ${table.name}`;
            rowModalBody.innerHTML = '';
            
            table.columns.forEach(column => {
                const div = document.createElement('div');
                div.className = 'mb-3';
                
                const label = document.createElement('label');
                label.className = 'form-label';
                label.textContent = `${column.name} (${column.type}${column.length ? `(${column.length})` : ''})`;
                
                let input;
                if (column.type === 'boolean') {
                    input = document.createElement('select');
                    input.className = 'form-select';
                    input.innerHTML = `
                        <option value="true">True</option>
                        <option value="false">False</option>
                    `;
                } else if (column.type === 'enum') {
                    input = document.createElement('select');
                    input.className = 'form-select';
                    const options = column.length.replace(/'/g, '').split(',');
                    options.forEach(option => {
                        input.innerHTML += `<option value="${option.trim()}">${option.trim()}</option>`;
                    });
                } else {
                    input = document.createElement('input');
                    input.type = column.type === 'date' ? 'date' : 
                                column.type === 'datetime' ? 'datetime-local' : 'text';
                    input.className = 'form-control';
                }
                
                input.id = `row_${column.name}`;
                input.required = !column.allowNull;
                
                div.appendChild(label);
                div.appendChild(input);
                rowModalBody.appendChild(div);
            });
            
            saveRowBtn.onclick = addRow;
            rowModal.show();
        }
        
        function showEditRowModal(index) {
            const table = database.tables[database.currentTable];
            const row = table.data[index];
            
            rowModalTitle.textContent = `Chỉnh sửa dòng #${index + 1} trong ${table.name}`;
            rowModalBody.innerHTML = '';
            
            table.columns.forEach(column => {
                const div = document.createElement('div');
                div.className = 'mb-3';
                
                const label = document.createElement('label');
                label.className = 'form-label';
                label.textContent = `${column.name} (${column.type}${column.length ? `(${column.length})` : ''})`;
                
                let input;
                if (column.type === 'boolean') {
                    input = document.createElement('select');
                    input.className = 'form-select';
                    input.innerHTML = `
                        <option value="true" ${row[column.name] === true ? 'selected' : ''}>True</option>
                        <option value="false" ${row[column.name] === false ? 'selected' : ''}>False</option>
                    `;
                } else if (column.type === 'enum') {
                    input = document.createElement('select');
                    input.className = 'form-select';
                    const options = column.length.replace(/'/g, '').split(',');
                    options.forEach(option => {
                        const opt = option.trim();
                        input.innerHTML += `<option value="${opt}" ${row[column.name] === opt ? 'selected' : ''}>${opt}</option>`;
                    });
                } else {
                    input = document.createElement('input');
                    input.type = column.type === 'date' ? 'date' : 
                                column.type === 'datetime' ? 'datetime-local' : 'text';
                    input.className = 'form-control';
                    input.value = row[column.name] || '';
                }
                
                input.id = `row_${column.name}`;
                input.required = !column.allowNull;
                
                div.appendChild(label);
                div.appendChild(input);
                rowModalBody.appendChild(div);
            });
            
            saveRowBtn.onclick = function() {
                updateRow(index);
            };
            rowModal.show();
        }
        
        function addRow() {
            const table = database.tables[database.currentTable];
            const newRow = {};
            
            table.columns.forEach(column => {
                const input = document.getElementById(`row_${column.name}`);
                let value = input.value;
                
                // Convert value based on type
                if (column.type === 'int') {
                    value = parseInt(value) || 0;
                } else if (column.type === 'decimal') {
                    value = parseFloat(value) || 0.0;
                } else if (column.type === 'boolean') {
                    value = value === 'true';
                }
                
                newRow[column.name] = value;
            });
            
            table.data.push(newRow);
            rowModal.hide();
            renderDataTable();
        }
        
        function updateRow(index) {
            const table = database.tables[database.currentTable];
            const updatedRow = {};
            
            table.columns.forEach(column => {
                const input = document.getElementById(`row_${column.name}`);
                let value = input.value;
                
                // Convert value based on type
                if (column.type === 'int') {
                    value = parseInt(value) || 0;
                } else if (column.type === 'decimal') {
                    value = parseFloat(value) || 0.0;
                } else if (column.type === 'boolean') {
                    value = value === 'true';
                }
                
                updatedRow[column.name] = value;
            });
            
            table.data[index] = updatedRow;
            rowModal.hide();
            renderDataTable();
        }
        
        function deleteRow(index) {
            if (!confirm('Bạn có chắc muốn xóa dòng này?')) return;
            
            const table = database.tables[database.currentTable];
            table.data.splice(index, 1);
            renderDataTable();
        }
        
        // Initialize
        updateUI();
    </script>
</body>
</html>