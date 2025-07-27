document.addEventListener('DOMContentLoaded', function() {
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

    // Hide and disable the primary key option for adding/editing columns
    // Make sure you have an element with id 'isPrimaryContainer' in your HTML (management_menu.php)
    const isPrimaryContainer = document.getElementById('isPrimaryContainer'); 
    if (isPrimaryContainer) {
        isPrimaryContainer.style.display = 'none'; // Hide the primary key option
    }
    const columnIsPrimaryCheckbox = document.getElementById('columnIsPrimary');
    if (columnIsPrimaryCheckbox) {
        columnIsPrimaryCheckbox.checked = false; // Ensure it's always false
        columnIsPrimaryCheckbox.disabled = true; // Disable it
    }
    
    const rowModal = new bootstrap.Modal(document.getElementById('rowModal'));
    const rowModalTitle = document.getElementById('rowModalTitle');
    const rowModalBody = document.getElementById('rowModalBody');
    const saveRowBtn = document.getElementById('saveRowBtn');

    // Global state
    let currentTable = null;
    let currentColumns = [];
    let currentData = [];
    let editingRowIndex = -1; // -1 for adding new row, otherwise index of row being edited
    
    // Event Listeners
    createTableBtn.addEventListener('click', createTable);
    existingTablesSelect.addEventListener('change', (e) => loadTable(e.target.value));
    addColumnBtn.addEventListener('click', () => showAddColumnModal());
    saveColumnBtn.addEventListener('click', saveColumn);
    saveStructureBtn.addEventListener('click', saveTableStructure); // This will call the placeholder now
    deleteTableBtn.addEventListener('click', deleteTable);
    addRowBtn.addEventListener('click', () => showAddRowModal());
    saveRowBtn.addEventListener('click', saveRow); // saveRow handles both add and update now

    columnTypeSelect.addEventListener('change', toggleLengthField);

    // Utility functions
    function toSnakeCase(str) {
        return str.replace(/\s+/g, '_').toLowerCase();
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrfToken"]')?.content || '';
    }

    // API Calls
    async function createTable() {
        const tableName = tableNameInput.value.trim();
        if (!tableName) {
            alert('Vui lòng nhập tên bảng.');
            return;
        }

        // 🔧 Tự lấy tiền tố path gốc (ví dụ: 'qlcv')
        const basePath = window.location.pathname.split('/')[1]; // 'qlcv' nếu URL là /qlcv/...
        const url = `/qlcv/dynamic-tables/create`;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({ table_name: tableName })
            });

            if (!response.ok) {
                const text = await response.text();
                throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
            }

            const data = await response.json();
            console.log("Phản hồi từ server (tạo bảng):", data);

            if (data.success) {
                alert(data.message);
                tableNameInput.value = '';

                const option = document.createElement('option');
                option.value = data.table_name;
                option.textContent = data.table_name;
                existingTablesSelect.appendChild(option);
                existingTablesSelect.value = data.table_name;
                loadTable(data.table_name);
            } else {
                alert('Lỗi khi tạo table: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Lỗi tạo table:', error);
            alert('Có lỗi xảy ra khi tạo table');
        }
    }

    async function loadTable(tableName) {
        if (!tableName) {
            tableStructureSection.style.display = 'none';
            return;
        }
        currentTable = tableName;
        try {
            const response = await fetch(`/dynamic-tables/structure?table=${tableName}`);
            const data = await response.json();

            if (data.success) {
                currentColumns = data.columns;
                currentData = data.data;
                updateUI();
                renderColumnsTable();
                renderDataTable();
            } else {
                alert('Lỗi khi tải cấu trúc table: ' + (data.message || 'Unknown error'));
                currentTable = null;
                currentColumns = [];
                currentData = [];
                updateUI();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải cấu trúc table');
            currentTable = null;
            currentColumns = [];
            currentData = [];
            updateUI();
        }
    }

    function updateUI() {
        if (currentTable) {
            tableStructureSection.style.display = 'block';
            currentTableNameSpan.textContent = currentTable;
            dataTableNameSpan.textContent = currentTable;
        } else {
            tableStructureSection.style.display = 'none';
        }
    }

    function renderColumnsTable() {
        columnsTableBody.innerHTML = '';
        currentColumns.forEach((column, index) => {
            const row = columnsTableBody.insertRow();
            row.insertCell().textContent = column.name;
            row.insertCell().innerHTML = `
                <span class="badge bg-secondary">${column.type.toUpperCase()}</span>
                ${column.isPrimary ? '<span class="badge bg-warning text-dark ms-1">PK</span>' : ''}
            `;
            row.insertCell().textContent = column.length || '-';
            row.insertCell().textContent = column.nullable ? 'Có' : 'Không';
            row.insertCell().textContent = column.default !== null ? column.default : '-';
            
            const actionsCell = row.insertCell();
            const editBtn = document.createElement('button');
            editBtn.className = 'btn btn-sm btn-info me-2';
            editBtn.innerHTML = '<i class="bi bi-pencil"></i>';
            editBtn.onclick = () => editColumn(index);
            actionsCell.appendChild(editBtn);

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'btn btn-sm btn-danger';
            deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
            deleteBtn.onclick = () => deleteColumn(column.name);
            actionsCell.appendChild(deleteBtn);
        });
    }

    function renderDataTable() {
        dataTableHeader.innerHTML = '';
        dataTableBody.innerHTML = '';
        rowCountSpan.textContent = currentData.length;

        // Render table headers
        const headerRow = dataTableHeader.insertRow();
        currentColumns.forEach(column => {
            const th = document.createElement('th');
            th.textContent = column.name;
            headerRow.appendChild(th);
        });
        const actionsTh = document.createElement('th');
        actionsTh.textContent = 'Hành động';
        headerRow.appendChild(actionsTh);

        // Render table body
        currentData.forEach((row, index) => {
            const tr = dataTableBody.insertRow();
            currentColumns.forEach(column => {
                const td = tr.insertCell();
                td.textContent = row[column.name] !== undefined ? row[column.name] : 'NULL';
            });

            const actionsTd = tr.insertCell();
            const editBtn = document.createElement('button');
            editBtn.className = 'btn btn-sm btn-info me-2';
            editBtn.innerHTML = '<i class="bi bi-pencil"></i>';
            editBtn.onclick = () => showEditRowModal(index);
            actionsTd.appendChild(editBtn);

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'btn btn-sm btn-danger';
            deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
            deleteBtn.onclick = () => deleteRow(index);
            actionsTd.appendChild(deleteBtn);
        });
    }

    function showAddColumnModal() {
        columnForm.reset();
        document.getElementById('columnModalTitle').textContent = 'Thêm cột mới';
        document.getElementById('columnOldNameContainer').style.display = 'none'; // Hide old name field for add
        document.getElementById('columnName').value = '';
        document.getElementById('columnLength').value = '';
        document.getElementById('columnNullable').checked = true;
        document.getElementById('columnDefault').value = '';

        // Hide and disable primary key option for new columns
        const isPrimaryContainer = document.getElementById('isPrimaryContainer');
        if (isPrimaryContainer) isPrimaryContainer.style.display = 'none';
        const columnIsPrimaryCheckbox = document.getElementById('columnIsPrimary');
        if (columnIsPrimaryCheckbox) {
            columnIsPrimaryCheckbox.checked = false;
            columnIsPrimaryCheckbox.disabled = true;
        }

        toggleLengthField();
        addColumnModal.show();
    }

    function editColumn(index) {
        const column = currentColumns[index];
        document.getElementById('columnModalTitle').textContent = 'Sửa cột';
        document.getElementById('columnOldNameContainer').style.display = 'block'; // Show old name field for edit
        document.getElementById('columnOldName').value = column.name;
        document.getElementById('columnName').value = column.name;
        document.getElementById('columnType').value = column.type;
        document.getElementById('columnLength').value = column.length || '';
        document.getElementById('columnNullable').checked = column.nullable;
        document.getElementById('columnDefault').value = column.default !== null ? column.default : '';

        // Hide and disable primary key option for editing columns
        const isPrimaryContainer = document.getElementById('isPrimaryContainer');
        if (isPrimaryContainer) isPrimaryContainer.style.display = 'none';
        const columnIsPrimaryCheckbox = document.getElementById('columnIsPrimary');
        if (columnIsPrimaryCheckbox) {
            columnIsPrimaryCheckbox.checked = column.isPrimary; // Still set for display, but disabled
            columnIsPrimaryCheckbox.disabled = true;
        }

        toggleLengthField();
        addColumnModal.show();
    }

    function toggleLengthField() {
        const type = columnTypeSelect.value;
        if (['string', 'varchar', 'int', 'integer', 'float', 'decimal'].includes(type)) { // Added int, integer, float, decimal
            lengthContainer.style.display = 'block';
        } else {
            lengthContainer.style.display = 'none';
        }
    }

    async function saveColumn() {
        const isEdit = document.getElementById('columnOldNameContainer').style.display === 'block';
        const oldColumnName = isEdit ? document.getElementById('columnOldName').value : null;

        const newColumn = {
            name: toSnakeCase(document.getElementById('columnName').value.trim()),
            type: document.getElementById('columnType').value,
            length: document.getElementById('columnLength').value,
            nullable: document.getElementById('columnNullable').checked,
            default: document.getElementById('columnDefault').value || null,
            // isPrimary is no longer user configurable, omitted from sending
        };

        if (!newColumn.name || !newColumn.type) {
            alert('Vui lòng nhập tên và kiểu cột.');
            return;
        }

        try {
            let endpoint = '';
            let body = {};

            if (isEdit) {
                endpoint = '/dynamic-tables/update-column';
                body = {
                    table_name: currentTable,
                    old_column_name: oldColumnName,
                    column: newColumn
                };
            } else {
                endpoint = '/dynamic-tables/add-column';
                body = {
                    table_name: currentTable,
                    column: newColumn
                };
            }

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify(body)
            });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                addColumnModal.hide();
                loadTable(currentTable); // Reload table structure and data
            } else {
                alert('Lỗi khi lưu cột: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi lưu cột');
        }
    }

    async function deleteColumn(columnName) {
        if (!confirm(`Bạn có chắc muốn xóa cột '${columnName}'? Hành động này sẽ xóa vĩnh viễn dữ liệu trong cột này.`)) return;

        try {
            const response = await fetch('/dynamic-tables/delete-column', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({
                    table_name: currentTable,
                    column_name: columnName
                })
            });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                loadTable(currentTable); // Reload table structure and data
            } else {
                alert('Lỗi khi xóa cột: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa cột');
        }
    }

    async function saveTableStructure() {
        // This button calls the placeholder now.
        // If you need to enable full save structure, you'd implement complex logic here
        // to compare currentColumns with server state and send adds/updates/deletes.
        // For now, it sends the current columns for the backend placeholder to acknowledge.
        try {
            const response = await fetch('/dynamic-tables/save-structure', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({
                    table_name: currentTable,
                    columns: currentColumns // Sending the current columns as is
                })
            });
            const data = await response.json();

            // Display message from the backend placeholder
            alert(data.message || 'Cấu trúc table đã được gửi cho backend.');

            if (!data.success) {
                console.warn('Save Table Structure (Placeholder) message:', data.message);
            }
            // No reload needed as this is just a placeholder action.
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi lưu cấu trúc table.');
        }
    }

    async function deleteTable() {
        if (!confirm(`Bạn có chắc muốn xóa table '${currentTable}' và toàn bộ dữ liệu của nó?`)) return;

        try {
            const response = await fetch('/dynamic-tables/delete-table', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({ table_name: currentTable })
            });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                // Remove from select dropdown
                const optionToRemove = existingTablesSelect.querySelector(`option[value="${currentTable}"]`);
                if (optionToRemove) {
                    optionToRemove.remove();
                }
                currentTable = null;
                currentColumns = [];
                currentData = [];
                existingTablesSelect.value = ''; // Reset select
                updateUI();
            } else {
                alert('Lỗi khi xóa table: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa table');
        }
    }

    // Row Data Management
    function showAddRowModal() {
        rowModalTitle.textContent = 'Thêm dòng mới';
        rowModalBody.innerHTML = ''; // Clear previous fields
        editingRowIndex = -1; // Indicate adding new row

        currentColumns.forEach(column => {
            // Exclude the 'id' column from the input form for new rows
            // as it's auto-incrementing.
            if (column.name === 'id' && column.isPrimary) return;

            const div = document.createElement('div');
            div.className = 'mb-3';
            const label = document.createElement('label');
            label.setAttribute('for', `row_${column.name}`);
            label.className = 'form-label';
            label.textContent = column.name + (column.nullable ? '' : ' *'); // Mark non-nullable fields

            let input;
            switch (column.type) {
                case 'text':
                    input = document.createElement('textarea');
                    input.className = 'form-control';
                    input.rows = 3;
                    break;
                case 'boolean':
                    input = document.createElement('select');
                    input.className = 'form-select';
                    input.innerHTML = '<option value="true">True</option><option value="false">False</option>';
                    break;
                case 'date':
                    input = document.createElement('input');
                    input.type = 'date';
                    input.className = 'form-control';
                    break;
                case 'datetime':
                    input = document.createElement('input');
                    input.type = 'datetime-local';
                    input.className = 'form-control';
                    break;
                default:
                    input = document.createElement('input');
                    input.type = ['integer', 'float', 'decimal'].includes(column.type) ? 'number' : 'text';
                    input.className = 'form-control';
            }
            input.id = `row_${column.name}`;
            input.name = column.name;
            input.required = !column.nullable;

            div.appendChild(label);
            div.appendChild(input);
            rowModalBody.appendChild(div);
        });

        rowModal.show();
    }

    function showEditRowModal(index) {
        rowModalTitle.textContent = 'Sửa dòng';
        rowModalBody.innerHTML = '';
        editingRowIndex = index;

        const rowToEdit = currentData[index];

        currentColumns.forEach(column => {
            const div = document.createElement('div');
            div.className = 'mb-3';
            const label = document.createElement('label');
            label.setAttribute('for', `row_${column.name}`);
            label.className = 'form-label';
            label.textContent = column.name + (column.nullable ? '' : ' *'); // Mark non-nullable fields

            let input;
            let currentValue = rowToEdit[column.name];

            switch (column.type) {
                case 'text':
                    input = document.createElement('textarea');
                    input.className = 'form-control';
                    input.rows = 3;
                    input.value = currentValue !== undefined ? currentValue : '';
                    break;
                case 'boolean':
                    input = document.createElement('select');
                    input.className = 'form-select';
                    input.innerHTML = `
                        <option value="true" ${currentValue === true ? 'selected' : ''}>True</option>
                        <option value="false" ${currentValue === false ? 'selected' : ''}>False</option>
                    `;
                    break;
                case 'date':
                    input = document.createElement('input');
                    input.type = 'date';
                    input.className = 'form-control';
                    input.value = currentValue ? new Date(currentValue).toISOString().split('T')[0] : '';
                    break;
                case 'datetime':
                    input = document.createElement('input');
                    input.type = 'datetime-local';
                    input.className = 'form-control';
                    input.value = currentValue ? new Date(currentValue).toISOString().slice(0, 16) : '';
                    break;
                default:
                    input = document.createElement('input');
                    // 'id' column should be read-only if it's the primary key and auto-incrementing
                    if (column.name === 'id' && column.isPrimary) {
                        input.type = 'text'; // Display as text
                        input.readOnly = true; // Make it read-only
                        input.value = currentValue !== undefined ? currentValue : '';
                    } else {
                        input.type = ['integer', 'float', 'decimal'].includes(column.type) ? 'number' : 'text';
                        input.value = currentValue !== undefined ? currentValue : '';
                    }
                    input.className = 'form-control';
            }
            input.id = `row_${column.name}`;
            input.name = column.name;
            input.required = !column.nullable;

            div.appendChild(label);
            div.appendChild(input);
            rowModalBody.appendChild(div);
        });

        rowModal.show();
    }

    async function saveRow() {
        const rowData = {};
        let isValid = true;

        currentColumns.forEach(column => {
            // Skip id if it's primary key and auto-incrementing for add new row scenario
            if (editingRowIndex === -1 && column.name === 'id' && column.isPrimary) {
                return;
            }

            const input = document.getElementById(`row_${column.name}`);
            if (!input) return; // Skip if input doesn't exist (e.g., id for new row)

            let value = input.value;

            // Type conversion
            if (column.type === 'integer' || column.type === 'int') {
                value = value === '' ? (column.nullable ? null : 0) : parseInt(value);
                if (isNaN(value) && !column.nullable) {
                    alert(`Vui lòng nhập một số nguyên hợp lệ cho cột '${column.name}'.`);
                    isValid = false;
                    return;
                }
            } else if (column.type === 'float' || column.type === 'decimal') {
                value = value === '' ? (column.nullable ? null : 0.0) : parseFloat(value);
                if (isNaN(value) && !column.nullable) {
                    alert(`Vui lòng nhập một số thập phân hợp lệ cho cột '${column.name}'.`);
                    isValid = false;
                    return;
                }
            } else if (column.type === 'boolean') {
                value = value === 'true';
            } else if (column.type === 'date') {
                value = value === '' ? (column.nullable ? null : '') : value; // Keep as string for date
            } else if (column.type === 'datetime') {
                value = value === '' ? (column.nullable ? null : '') : value; // Keep as string for datetime
            }

            if (!column.nullable && (value === null || value === '' || (typeof value === 'number' && isNaN(value)))) {
                alert(`Cột '${column.name}' không được để trống.`);
                isValid = false;
                return;
            }
            rowData[column.name] = value;
        });

        if (!isValid) return;

        try {
            let endpoint = '';
            let body = {
                table_name: currentTable,
                row_data: rowData
            };

            if (editingRowIndex === -1) { // Add new row
                endpoint = '/dynamic-tables/add-row';
            } else { // Update existing row
                endpoint = '/dynamic-tables/update-row';
                body.row_id = currentData[editingRowIndex].id; // Assuming 'id' is the primary key
            }

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify(body)
            });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                rowModal.hide();
                loadTable(currentTable); // Reload data from server
            } else {
                alert('Lỗi khi lưu dòng: ' + (data.message || 'Unknown error') + (data.errors ? JSON.stringify(data.errors) : ''));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi lưu dòng');
        }
    }
    
    async function deleteRow(index) {
        if (!confirm('Bạn có chắc muốn xóa dòng này?')) return;
        
        const rowId = currentData[index].id; // Assuming 'id' is the primary key
        
        try {
            const response = await fetch('/dynamic-tables/delete-row', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({
                    table_name: currentTable,
                    row_id: rowId
                })
            });
            const data = await response.json();

            if (data.success) {
                loadTable(currentTable); // Reload data from server
            } else {
                alert('Lỗi khi xóa dòng: ' + (data.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa dòng');
        }
    }
    
    // Initialize
    updateUI();
});