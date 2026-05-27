
<?php $__env->startSection('title', 'Dataset Donation - Keywords - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="donation-page">
    <div class="container">
        <!-- Header -->
        <div class="donation-header">
            <h1 class="page-title">Dataset Donation Form</h1>
            <p class="page-description">
                We offer users the option to upload their dataset data to our repository.
            </p>
            <p class="page-description">
                Users can provide tabular or non-tabular dataset data which will be made publicly available on our repository. 
                Donators are free to edit their donated datasets, but edits must be approved before finalizing.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 71%"></div>
            </div>
            <span class="progress-text">Page 5 / 7</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.keywords.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!-- Keywords Section -->
            <div class="form-card">
                <h5 class="card-section-title">Optional: Add Keywords</h5>
                
                <p class="text-muted mb-3">
                    Search for an existing keyword below, or type a new keyword and press Enter.
                </p>

                <!-- Keyword Input -->
                <div class="keyword-input-container mb-4">
                    <input type="text" 
                           class="form-control" 
                           id="keyword_input"
                           placeholder="Add keywords..."
                           autocomplete="off">
                    <div class="form-hint mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Press <kbd>Enter</kbd> to add a keyword
                    </div>
                </div>

                <!-- Keyword Suggestions (Auto-complete) -->
                <div id="keyword_suggestions" class="keyword-suggestions mb-3" style="display: none;">
                    <div class="suggestion-header">
                        <small class="text-muted">Suggested keywords:</small>
                    </div>
                    <div class="suggestions-list" id="suggestions_list">
                        <!-- Dynamic suggestions will appear here -->
                    </div>
                </div>

                <!-- Selected Keywords -->
                <div id="selected_keywords" class="selected-keywords">
                    <h6 class="mb-3">Selected Keywords:</h6>
                    <div id="keywords_container" class="keywords-container">
                        <?php
                            $keywords = old('keywords', $keywordsData ?? []);
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $keywords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="keyword-tag">
                                <?php echo e($keyword); ?>

                                <button type="button" class="remove-keyword" onclick="removeKeyword('<?php echo e($keyword); ?>', <?php echo e($index); ?>)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted small">No keywords added yet.</p>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="keywords" id="keywords_input" value="<?php echo e(json_encode($keywords)); ?>">
                </div>
            </div>

            <!-- Popular Keywords Section -->
            <div class="form-card">
                <h5 class="card-section-title">Popular Keywords</h5>
                <p class="text-muted mb-3">
                    Click on any keyword below to quickly add it to your dataset.
                </p>
                
                <div class="popular-keywords">
                    <?php
                        $popularKeywords = [
                            'Classification', 'Regression', 'Clustering', 'Machine Learning',
                            'Deep Learning', 'Neural Networks', 'Data Mining', 'Pattern Recognition',
                            'Natural Language Processing', 'Computer Vision', 'Time Series',
                            'Image Processing', 'Text Mining', 'Supervised Learning',
                            'Unsupervised Learning', 'Reinforcement Learning', 'Feature Extraction',
                            'Dimensionality Reduction', 'Ensemble Methods', 'Cross Validation'
                        ];
                    ?>
                    
                    <?php $__currentLoopData = $popularKeywords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="keyword-chip" onclick="addKeyword('<?php echo e($keyword); ?>')">
                            <?php echo e($keyword); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.files')); ?>" class="btn-back me-3">
                    <i class="bi bi-arrow-left me-2"></i>BACK
                </a>
                <button type="submit" class="btn-next">
                    NEXT <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-title {
        padding-top: 50px;
        color: #0077b6;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .page-description {
        color: #555;
        line-height: 1.7;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .progress-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    
    .progress {
        flex: 1;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .progress-text {
        font-size: 0.85rem;
        color: #6c757d;
        white-space: nowrap;
    }
    
    .form-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .card-section-title {
        color: #0077b6;
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 1.5rem;
    }
    
    .form-control {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    
    .form-control:focus {
        border-color: #0077b6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,119,182,0.12);
    }
    
    .form-hint {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .form-hint kbd {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 0.1rem 0.4rem;
        font-family: monospace;
        font-size: 0.85rem;
    }
    
    /* Keyword Suggestions */
    .keyword-suggestions {
        background-color: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .suggestion-header {
        margin-bottom: 0.75rem;
    }
    
    .suggestions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .suggestion-item {
        background-color: #e9f5f9;
        color: #0077b6;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
        border: 1px solid #0077b6;
    }
    
    .suggestion-item:hover {
        background-color: #0077b6;
        color: white;
    }
    
    /* Selected Keywords */
    .selected-keywords {
        margin-top: 2rem;
    }
    
    .keywords-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        min-height: 50px;
        padding: 1rem;
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    
    .keyword-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: #0077b6;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .remove-keyword {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
        padding: 0;
        font-size: 1.1rem;
        line-height: 1;
        opacity: 0.8;
        transition: opacity 0.2s;
    }
    
    .remove-keyword:hover {
        opacity: 1;
    }
    
    /* Popular Keywords */
    .popular-keywords {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .keyword-chip {
        display: inline-block;
        background-color: white;
        color: #0077b6;
        padding: 0.5rem 1rem;
        border: 2px solid #0077b6;
        border-radius: 20px;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .keyword-chip:hover {
        background-color: #0077b6;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,119,182,0.3);
    }
    
    .keyword-chip.added {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .btn-back {
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
        font-weight: 700;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-back:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-next {
        background-color: #0077b6;
        color: white;
        font-weight: 700;
        padding: 0.75rem 2.5rem;
        border: none;
        border-radius: 6px;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-next:hover {
        background-color: #005f73;
    }
    
    .form-navigation {
        display: flex;
        justify-content: flex-start;
        margin-top: 2rem;
        margin-bottom: 3rem;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem 1rem;
        }
        
        .form-card {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .popular-keywords {
            gap: 0.5rem;
        }
        
        .keyword-chip {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let keywords = <?php echo json_encode($keywordsData ?? old('keywords', []), 512) ?>;
const allKeywords = <?php echo json_encode($allKeywords ?? [], 15, 512) ?>;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateKeywordsDisplay();
    
    // Input handler
    const input = document.getElementById('keyword_input');
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const keyword = this.value.trim();
            if (keyword) {
                addKeyword(keyword);
                this.value = '';
            }
        }
    });
    
    // Show suggestions on input
    input.addEventListener('input', function() {
        const value = this.value.trim().toLowerCase();
        if (value.length > 0) {
            showSuggestions(value);
        } else {
            hideSuggestions();
        }
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.keyword-input-container')) {
            hideSuggestions();
        }
    });
});

// Add keyword
function addKeyword(keyword) {
    const normalizedKeyword = keyword.trim();
    
    // Check if already exists
    if (keywords.includes(normalizedKeyword)) {
        showNotification('Keyword already added!', 'warning');
        return;
    }
    
    keywords.push(normalizedKeyword);
    updateKeywordsDisplay();
    updateHiddenInput();
    
    // Mark as added in popular keywords
    const chip = document.querySelector(`.keyword-chip[onclick="addKeyword('${normalizedKeyword}')"]`);
    if (chip) {
        chip.classList.add('added');
    }
    
    showNotification(`Keyword "${normalizedKeyword}" added`, 'success');
}

// Remove keyword
function removeKeyword(keyword, index) {
    keywords.splice(index, 1);
    updateKeywordsDisplay();
    updateHiddenInput();
    
    // Unmark in popular keywords
    const chip = document.querySelector(`.keyword-chip[onclick="addKeyword('${keyword}')"]`);
    if (chip) {
        chip.classList.remove('added');
    }
}

// Update display
function updateKeywordsDisplay() {
    const container = document.getElementById('keywords_container');
    
    if (keywords.length === 0) {
        container.innerHTML = '<p class="text-muted small">No keywords added yet.</p>';
    } else {
        container.innerHTML = keywords.map((keyword, index) => `
            <span class="keyword-tag">
                ${keyword}
                <button type="button" class="remove-keyword" onclick="removeKeyword('${keyword}', ${index})">
                    <i class="bi bi-x"></i>
                </button>
            </span>
        `).join('');
    }
}

// Update hidden input for form submission
function updateHiddenInput() {
    document.getElementById('keywords_input').value = JSON.stringify(keywords);
}

// Show suggestions
function showSuggestions(query) {
    const suggestionsDiv = document.getElementById('keyword_suggestions');
    const suggestionsList = document.getElementById('suggestions_list');
    
    // Filter keywords that match query and are not already added
    const filtered = allKeywords.filter(kw => 
        kw.toLowerCase().includes(query) && !keywords.includes(kw)
    ).slice(0, 10); // Limit to 10 suggestions
    
    if (filtered.length > 0) {
        suggestionsList.innerHTML = filtered.map(kw => `
            <span class="suggestion-item" onclick="addKeyword('${kw}')">${kw}</span>
        `).join('');
        
        suggestionsDiv.style.display = 'block';
    } else {
        hideSuggestions();
    }
}

// Hide suggestions
function hideSuggestions() {
    document.getElementById('keyword_suggestions').style.display = 'none';
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'warning' ? 'warning' : 'success'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    // Keywords are optional, so no validation needed
    // But you can add validation if required:
    // if (keywords.length === 0) {
    //     e.preventDefault();
    //     showNotification('Please add at least one keyword', 'warning');
    //     return false;
    // }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/keywords.blade.php ENDPATH**/ ?>