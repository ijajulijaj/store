document.addEventListener('DOMContentLoaded', function () {
    const modifiedProducts = {};
    const container = document.getElementById('modified-products-container');
    const tbody = document.getElementById('productsTableBody');
    const deleteStates = {};

    function updateHiddenFields() {
        container.innerHTML = '';

        Object.entries(modifiedProducts).forEach(([prodId, product]) => {
            if (!product.modified) return;

            const qtyInput = document.createElement('input');
            qtyInput.type = 'hidden';
            qtyInput.name = `modified_products[${prodId}][qty]`;
            qtyInput.value = product.qty;
            container.appendChild(qtyInput);

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = `modified_products[${prodId}][action]`;
            actionInput.value = product.action;
            container.appendChild(actionInput);

            if (product.isNew) {
                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = `modified_products[${prodId}][detail]`;
                nameInput.value = product.detail;
                container.appendChild(nameInput);

                const priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = `modified_products[${prodId}][price]`;
                priceInput.value = product.price;
                container.appendChild(priceInput);
            }
        });
    }

    function handleQtyChange(e) {
        const input = e.target;
        const prodId = input.dataset.prodId;
        const newQty = parseInt(input.value);
        const row = input.closest('tr');

        if (!modifiedProducts[prodId]) return;

        modifiedProducts[prodId].qty = newQty;
        modifiedProducts[prodId].modified = true;

        if (newQty === 0) {
            row.classList.add('marked-for-delete');
            modifiedProducts[prodId].action = 'delete';
            deleteStates[prodId] = true;
            input.readOnly = true;
            row.querySelector('.delete-product').disabled = true;
            row.querySelector('.delete-product').innerHTML = '<i class="bi bi-trash"></i> Deleted';
        } else {
            row.classList.remove('marked-for-delete');
            modifiedProducts[prodId].action = modifiedProducts[prodId].isNew ? 'add' : 'update';
            delete deleteStates[prodId];
            input.readOnly = false;
            row.querySelector('.delete-product').disabled = false;
            row.querySelector('.delete-product').innerHTML = '<i class="bi bi-trash"></i> Delete';
        }

        const price = parseFloat(row.querySelector('td:nth-child(5)').textContent);
        row.querySelector('td:nth-child(6)').textContent = (price * newQty).toFixed(2) + ' BDT';

        updateHiddenFields();
    }

    function handleDelete(e) {
        const button = e.target.closest('.delete-product');
        const prodId = button.dataset.prodId;
        const row = button.closest('tr');
        const input = row.querySelector('.qty-input');

        if (modifiedProducts[prodId]?.isNew) {
            row.remove();
            delete modifiedProducts[prodId];
            delete deleteStates[prodId];
        } else {
            input.value = 0;
            input.dispatchEvent(new Event('change'));
        }

        updateHiddenFields();
    }

    // Initialize from existing rows
    document.querySelectorAll('.qty-input').forEach(input => {
        const prodId = input.dataset.prodId;
        const originalQty = parseInt(input.dataset.originalQty);
        const currentQty = parseInt(input.value);
        const row = input.closest('tr');
        const isNew = row.classList.contains('new-product');

        if (row.classList.contains('marked-for-delete')) {
            deleteStates[prodId] = true;
            modifiedProducts[prodId] = {
                qty: 0,
                action: 'delete',
                modified: true,
                isNew: isNew
            };
        } else {
            modifiedProducts[prodId] = {
                qty: currentQty,
                action: isNew ? 'add' : 'update',
                modified: currentQty !== originalQty,
                isNew: isNew
            };
        }
    });

    // Add product button handler
    document.querySelectorAll('.add-to-order').forEach(button => {
        button.addEventListener('click', function () {
            const prodId = this.dataset.prodId;

            if (modifiedProducts[prodId]) {
                alert('Product already in order!');
                return;
            }

            modifiedProducts[prodId] = {
                qty: 1,
                action: 'add',
                modified: true,
                isNew: true,
                name: this.dataset.productName,
                detail: this.dataset.productDetail,
                price: parseFloat(this.dataset.price)
            };

            const newRow = document.createElement('tr');
            newRow.classList.add('new-product');
            newRow.innerHTML = `
                <td>${tbody.children.length + 1}</td>
                <td>${prodId}</td>
                <td>${this.dataset.productName} ${this.dataset.productDetail}</td>
                <td>
                    <input type="number" 
                           class="form-control qty-input"
                           value="1"
                           min="0"
                           max="100"
                           data-prod-id="${prodId}"
                           data-original-qty="0">
                </td>
                <td>${parseFloat(this.dataset.price).toFixed(2)} BDT</td>
                <td class="total-price">${parseFloat(this.dataset.price).toFixed(2)} BDT</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-product" 
                            data-prod-id="${prodId}">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
            `;
            tbody.appendChild(newRow);

            const newInput = newRow.querySelector('.qty-input');
            newInput.addEventListener('change', handleQtyChange);
            newRow.querySelector('.delete-product').addEventListener('click', handleDelete);

            updateHiddenFields();
        });
    });

    // Attach handlers
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', handleDelete);
    });
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', handleQtyChange);
    });

    // Before submitting form
    const form = document.getElementById('productsForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            updateHiddenFields();

            const deleteStatesInput = document.createElement('input');
            deleteStatesInput.type = 'hidden';
            deleteStatesInput.name = 'delete_states';
            deleteStatesInput.value = JSON.stringify(deleteStates);
            container.appendChild(deleteStatesInput);

            const hasChanges = Object.values(modifiedProducts).some(p => p.modified);
            if (!hasChanges) {
                e.preventDefault();
                alert('No changes detected!');
            }
        });
    }
});
