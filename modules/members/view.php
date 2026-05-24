<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';
?>

<div class="dashboard-sections" style="display:block;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 class="section-title" style="margin-bottom:0;">View All Members</h2>
        <a href="add.php" class="btn"><i class="fas fa-plus"></i> Add New Member</a>
    </div>

    <div class="filters-row">
        <input type="text" id="searchInput" class="search-bar" style="margin-bottom:0; flex:2;" placeholder="Search by name or mobile...">
        <select id="filterSelect" class="form-control" style="flex:1;">
            <option value="All">All Status</option>
            <option value="Active">Active</option>
            <option value="Expired">Expired</option>
            <option value="Expiring Soon">Expiring Soon</option>
        </select>
    </div>

    <div class="table-responsive">
        <table id="membersTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Plan</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populated via AJAX -->
                <tr><td colspan="7" style="text-align:center;">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Members > View Members';

const searchInput = document.getElementById('searchInput');
const filterSelect = document.getElementById('filterSelect');
const tableBody = document.querySelector('#membersTable tbody');

function fetchMembers() {
    const search = encodeURIComponent(searchInput.value);
    const filter = encodeURIComponent(filterSelect.value);
    
    fetch(BASE_URL + `api/members_api.php?search=${search}&filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                console.error(data.error);
                return;
            }
            
            tableBody.innerHTML = '';
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No members found.</td></tr>';
                return;
            }
            
            data.forEach(m => {
                let badgeClass = 'active';
                if(m.status === 'Expired') badgeClass = 'expired';
                if(m.status === 'Expiring Soon') badgeClass = 'soon';
                
                const planName = m.plan_name ? m.plan_name : 'No Plan Assigned';
                const expiry = m.expiry_date ? m.expiry_date : 'N/A';
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>#${m.member_id}</td>
                    <td><strong>${m.name}</strong></td>
                    <td>${m.mobile}</td>
                    <td>${planName}</td>
                    <td>${expiry}</td>
                    <td><span class="badge badge-${badgeClass}">${m.status}</span></td>
                    <td>
                        <a href="edit.php?id=${m.member_id}" class="btn" style="padding:5px 10px; font-size:0.8rem;"><i class="fas fa-edit"></i></a>
                        <a href="delete.php?id=${m.member_id}" class="btn btn-danger" style="padding:5px 10px; font-size:0.8rem;" onclick="return confirmDelete('Delete member ${m.name}?');"><i class="fas fa-trash"></i></a>
                    </td>
                `;
                tableBody.appendChild(tr);
            });
        });
}

searchInput.addEventListener('keyup', fetchMembers);
filterSelect.addEventListener('change', fetchMembers);

// Initial fetch
fetchMembers();
</script>

<?php require_once '../../includes/footer.php'; ?>
