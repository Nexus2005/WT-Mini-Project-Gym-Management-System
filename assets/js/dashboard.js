document.addEventListener('DOMContentLoaded', () => {
    // Sidebar Toggle
    const hasSubmenus = document.querySelectorAll('.has-submenu > a');
    hasSubmenus.forEach(menu => {
        menu.addEventListener('click', (e) => {
            e.preventDefault();
            const submenu = menu.nextElementSibling;
            if(submenu) {
                submenu.classList.toggle('active');
                const icon = menu.querySelector('.toggle-icon');
                if(submenu.classList.contains('active')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }
        });
    });

    // Flash message auto dismiss after 3s
    const flashMessages = document.querySelectorAll('.flash-alert');
    if (flashMessages) {
        setTimeout(() => {
            flashMessages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 3000);
    }
});

// Used in Member creation
function confirmMemberAdd(nameFieldId) {
    const nameValue = document.getElementById(nameFieldId).value;
    if(!nameValue) return false;
    
    return prompt("Confirm member name:", nameValue) !== null;
}

// Delete confirmation
function confirmDelete(message = "Are you sure you want to delete this?") {
    return confirm(message);
}
