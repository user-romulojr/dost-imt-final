function toggleProfileDropdown() {
    var content = document.getElementById("dropdown-profile");

    if(content.style.display == "none" || content.style.display == "") {
        content.style.display = "block";
    } else {
        content.style.display = "none";
    }
}

// Open Create User Dialog
document.getElementById('openCreateDialog').addEventListener('click', function() {
    document.getElementById('createDialog').showModal();
});

// Close Create User Dialog
document.getElementById('closeCreateDialog').addEventListener('click', function() {
    document.getElementById('createDialog').close();
});


// Close Edit User Dialog
document.getElementById('closeEditDialog').addEventListener('click', function() {
    document.getElementById('editDialog').close();
});


function toggleContent(content, dropdown) {
    var content = document.getElementById(content);
    var dropdownIcon = document.getElementById(dropdown);

    if(content.style.display == "none" || content.style.display == "") {
        content.style.display = "block";
        dropdownIcon.style.transform = "rotate(180deg)";
    } else {
        content.style.display = "none";
        dropdownIcon.style.transform = "";
    }
}

function collapseSidebar() {
    var sidebar = document.getElementById("sidebar");
    var main_content = document.getElementById("main-content-id");
    var collapse_icon = document.getElementById("collapse-icon-id");

    if(sidebar.style.display == "none" || sidebar.style.display == "") {
        sidebar.style.display = "flex";
        main_content.style.width = "80vw";
        collapse_icon.style.transform = "";
    } else {
        sidebar.style.display = "none";
        main_content.style.width = "98.7vw";
        collapse_icon.style.transform = "rotate(180deg)";
    }
}
