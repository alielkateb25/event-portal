/**
 * Event Portal - Main JavaScript
 * Student C - API & Features Lead
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Event Portal JS Loaded');

    // 1. Dynamic Event Loading (for homepage)
    const eventsContainer = document.getElementById('api-events-container');
    if (eventsContainer) {
        loadEventsViaAPI();
    }

    // 2. Ajax Registration
    setupRegistrationAjax();

    // 3. Ajax Review Submission
    setupReviewAjax();
});

/**
 * Load events from API and render them
 */
async function loadEventsViaAPI() {
    const container = document.getElementById('api-events-container');
    
    try {
        const response = await fetch('/api/events', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const events = await response.json();
        
        if (events.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No events available.</div>';
            return;
        }
        
        container.innerHTML = events.map(event => `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${escapeHtml(event.title)}</h5>
                        <p class="card-text">${escapeHtml(event.description.substring(0, 100))}...</p>
                        <p class="card-text">
                            <small class="text-muted">
                                📅 ${event.eventDate}<br>
                                📍 ${escapeHtml(event.location)}<br>
                                ${event.availableSeats !== null ? `🎟️ ${event.availableSeats} seats left` : '♾️ Unlimited'}
                            </small>
                        </p>
                        <a href="/event/${event.id}" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        `).join('');
        
    } catch (error) {
        console.error('Error loading events:', error);
        container.innerHTML = '<div class="alert alert-danger">Failed to load events.</div>';
    }
}

/**
 * Setup Ajax for event registration
 */
function setupRegistrationAjax() {
    const registerForm = document.querySelector('form[action*="/register"]');
    const unregisterForm = document.querySelector('form[action*="/unregister"]');
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegistration);
    }
    
    if (unregisterForm) {
        unregisterForm.addEventListener('submit', handleUnregistration);
    }
}

/**
 * Handle registration with Ajax
 */
async function handleRegistration(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    try {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Registering...';
        
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Reload to show updated state
            window.location.reload();
        } else {
            alert(result.errors?.join(', ') || 'Registration failed');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
        
    } catch (error) {
        console.error('Registration error:', error);
        alert('Registration failed. Please try again.');
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
}

/**
 * Handle unregistration with Ajax
 */
async function handleUnregistration(e) {
    e.preventDefault();
    
    if (!confirm('Are you sure you want to cancel your registration?')) {
        return;
    }
    
    const form = e.target;
    
    try {
        await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Reload to show updated state
        window.location.reload();
        
    } catch (error) {
        console.error('Unregistration error:', error);
        alert('Cancellation failed. Please try again.');
    }
}

/**
 * Setup Ajax for reviews (optional enhancement)
 */
function setupReviewAjax() {
    const reviewForm = document.querySelector('#review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', handleReviewSubmit);
    }
}

/**
 * Handle review submission
 */
async function handleReviewSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/api/reviews', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        } else {
            alert('Review submission failed');
        }
        
    } catch (error) {
        console.error('Review error:', error);
        alert('Failed to submit review');
    }
}

/**
 * Utility: Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}