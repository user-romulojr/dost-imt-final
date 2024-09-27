function toggleProfileDropdown() {
    var content = document.getElementById("dropdown-profile");

    if(content.style.display == "none" || content.style.display == "") {
        content.style.display = "block";
    } else {
        content.style.display = "none";
    }
}

document.getElementById('openCreateDialog').addEventListener('click', function() {
    document.getElementById('createDialog').showModal();
});


document.getElementById('closeCreateDialog').addEventListener('click', function() {
    document.getElementById('createDialog').close();
});


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
    var nav_container_id = document.getElementById("nav-container-id");
    var sidebar = document.getElementById("sidebar");
    var main_content = document.getElementById("main-content-id");
    var collapse_icon = document.getElementById("collapse-icon-id");

    if(nav_container_id.style.display == "none" || nav_container_id.style.display == "") {
        main_content.style.width = "83.8vw"; /* ADJUST HERE */
        nav_container_id.style.width = "15vw"; /* ADJUST HERE */
        sidebar.style.padding = "12px";
        sidebar.style.paddingRight = "0";
        nav_container_id.style.display = "flex";
        collapse_icon.style.transform = "";
    } else {
        main_content.style.width = "98.8vw"; /* ADJUST HERE */
        nav_container_id.style.display = "none";
        sidebar.style.padding = "0";
        collapse_icon.style.transform = "rotate(180deg)";
    }
}


function setDefault(formFields){
    document.getElementById('dropdown-content-id').display = "none";

    formFields.forEach(function(formField) {
        document.getElementById("filter_" + formField + "_id").selectedIndex = 0;
    });
}


