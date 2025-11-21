// Form Validation
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var username = document.getElementById('username').value;
    var email = document.getElementById('email').value;
    if (username === '' || email === '') {
        alert('Please fill in all required fields.');
        event.preventDefault();
    }
});

// Filter Notes
function filterNotes() {
    var year = document.getElementById('year').value;
    var subject = document.getElementById('subject').value;
    var notes = document.getElementsByClassName('note');

    for (var i = 0; i < notes.length; i++) {
        if (notes[i].dataset.year === year && notes[i].dataset.subject === subject) {
            notes[i].style.display = 'block';
        } else {
            notes[i].style.display = 'none';
        }
    }
}
document.getElementById('searchButton').addEventListener('click', filterNotes);

// Image Preview
document.getElementById('profileImage').addEventListener('change', function(event) {
    var reader = new FileReader();
    reader.onload = function() {
        document.getElementById('imagePreview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
});

// Toggle Dropdown Menu
document.getElementById('dropdownToggle').addEventListener('click', function() {
    document.getElementById('dropdownMenu').classList.toggle('show');
});

// Load Notes Dynamically
function loadNotes() {
    showLoadingSpinner();
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'notes.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('notesContainer').innerHTML = xhr.responseText;
        }
        hideLoadingSpinner();
    };
    xhr.send();
}
document.getElementById('loadNotesButton').addEventListener('click', loadNotes);

// Smooth Scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(event) {
        event.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Show Loading Spinner
function showLoadingSpinner() {
    var spinner = document.createElement('div');
    spinner.classList.add('spinner');
    document.body.appendChild(spinner);
}

// Hide Loading Spinner
function hideLoadingSpinner() {
    var spinner = document.querySelector('.spinner');
    if (spinner) {
        spinner.remove();
    }
}

// Fade In Content
document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('fade-in');
});

// Toggle Navbar
document.getElementById('navbarToggle').addEventListener('click', function() {
    var navbar = document.getElementById('navbar');
    if (navbar.classList.contains('open')) {
        navbar.classList.remove('open');
    } else {
        navbar.classList.add('open');
    }
});

// Debounce Function
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Filter Notes with Debounce
const debouncedFilterNotes = debounce(filterNotes, 300);
document.getElementById('searchInput').addEventListener('input', debouncedFilterNotes);

// Logout Confirmation
document.getElementById('logoutLink').addEventListener('click', function(event) {
    event.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
    }
});
