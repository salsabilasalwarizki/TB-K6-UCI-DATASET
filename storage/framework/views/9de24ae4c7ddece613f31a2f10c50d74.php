
<?php $__env->startSection('title', 'Link External Dataset - Keywords - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>

<div class="linking-page">
    <div class="container">
        <!-- Header -->
        <div class="linking-header">
            <h1 class="page-title">Dataset Linking Form</h1>
            <p class="page-description">
                We offer users the option to list a dataset in our dataset index without actually hosting the data itself in our repository.
            </p>
            <p class="page-description">
                Instead, users can provide a link to an external webpage from which the dataset can be downloaded. Linking a dataset in 
                our repository can help increase the dataset's visibility and also allows users to use our dataset filtering and search 
                capabilities to identify if the dataset is useful for them.
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-wrapper">
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 66.67%"></div>
            </div>
            <span class="progress-text">Page 4 / 6</span>
        </div>

        <!-- Form -->
        <form action="<?php echo e(route('contribute.linking.keywords.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-card">
                <h5 class="card-section-title">Optional: Add Keywords</h5>
                <p class="text-muted mb-3">
                    Search for an existing keyword below, or type a new keyword and press Enter.
                </p>

                <div class="keyword-input-wrapper">
                    <input 
                        type="text" 
                        class="form-control" 
                        id="keywordInput" 
                        placeholder="Add keywords..."
                        autocomplete="off"
                    >
                    <div id="keywordDropdown" class="keyword-dropdown"></div>
                    <div id="keywordTags" class="keyword-tags mt-3"></div>
                    <input type="hidden" name="keywords" id="keywordsHidden">
                </div>
            </div>

            <!-- Navigation -->
            <div class="form-navigation">
                <a href="<?php echo e(route('contribute.linking.creators')); ?>" class="btn-back">
                    BACK
                </a>
                <button type="submit" class="btn-next">
                    NEXT
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .linking-page { max-width: 900px; margin: 0 auto; }
    .page-title { padding-top: 50px; color: #0077b6; font-weight: 700; font-size: 2rem; margin-bottom: 1.5rem; }
    .page-description { color: #555; line-height: 1.7; font-size: 0.95rem; margin-bottom: 0.5rem; }
    .progress-wrapper { display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem; }
    .progress { flex: 1; height: 8px; background-color: #e9ecef; border-radius: 4px; overflow: hidden; }
    .progress-bar.bg-warning { background-color: #ffd60a !important; }
    .progress-text { font-size: 0.85rem; color: #6c757d; white-space: nowrap; }
    .form-card { background: white; border: 1px solid #e0e0e0; border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem; }
    .card-section-title { color: #0077b6; font-weight: 600; font-size: 1.3rem; margin-bottom: 0.5rem; }
    
    /* Keyword Input Wrapper */
    .keyword-input-wrapper { position: relative; }
    .form-control { border-radius: 8px; border: 1px solid #dee2e6; padding: 0.7rem 1rem; font-size: 0.95rem; }
    .form-control:focus { border-color: #0077b6; box-shadow: 0 0 0 3px rgba(0,119,182,0.1); }
    
    /* Keyword Dropdown (Autocomplete) */
    .keyword-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-top: 4px;
        max-height: 300px;
        overflow-y: auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1000;
        display: none;
    }
    .keyword-dropdown.show { display: block; }
    .keyword-dropdown-item {
        padding: 0.65rem 1rem;
        cursor: pointer;
        transition: background 0.15s;
    }
    .keyword-dropdown-item:hover,
    .keyword-dropdown-item.active {
        background-color: #f0f4f8;
    }
    
    /* Keyword Tags */
    .keyword-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .keyword-tag {
        background-color: #e9f5f9;
        color: #005f73;
        border: 1px solid #0077b6;
        border-radius: 20px;
        padding: 0.4rem 0.9rem;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .keyword-tag .remove-tag {
        cursor: pointer;
        color: #0077b6;
        font-weight: 900;
        font-size: 1.1rem;
        line-height: 1;
    }
    .keyword-tag .remove-tag:hover { color: #dc3545; }
    
    /* Navigation */
    .form-navigation { display: flex; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem; }
    .btn-back { background-color: #fff; color: #dc3545; border: 1px solid #dc3545; font-weight: 700; padding: 0.75rem 2.5rem; border-radius: 6px; text-decoration: none; }
    .btn-back:hover { background-color: #dc3545; color: white; }
    .btn-next { background-color: #0077b6; color: white; font-weight: 700; padding: 0.75rem 2.5rem; border: none; border-radius: 6px; cursor: pointer; }
    .btn-next:hover { background-color: #005f73; }
    
    @media (max-width: 768px) { 
        .container { padding: 1.5rem 1rem; } 
        .form-card { padding: 1.25rem; } 
        .form-navigation { flex-direction: column; }
        .btn-back, .btn-next { width: 100%; text-align: center; } 
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('keywordInput');
    const dropdown = document.getElementById('keywordDropdown');
    const tagsContainer = document.getElementById('keywordTags');
    const hiddenInput = document.getElementById('keywordsHidden');
    let keywords = [];
    let allKeywords = <?php echo json_encode($allKeywords ?? [], 15, 512) ?>;
    let activeIndex = -1;

    function updateHiddenInput() {
        hiddenInput.value = JSON.stringify(keywords);
    }

    function renderTags() {
        tagsContainer.innerHTML = '';
        keywords.forEach(kw => {
            const tag = document.createElement('div');
            tag.className = 'keyword-tag';
            tag.innerHTML = `${kw} <span class="remove-tag" data-kw="${kw}">&times;</span>`;
            tagsContainer.appendChild(tag);
        });
    }

    function showDropdown(filtered) {
        dropdown.innerHTML = '';
        if (filtered.length === 0) {
            dropdown.classList.remove('show');
            return;
        }
        filtered.forEach((kw, index) => {
            const item = document.createElement('div');
            item.className = 'keyword-dropdown-item';
            item.textContent = kw;
            item.addEventListener('click', () => selectKeyword(kw));
            dropdown.appendChild(item);
        });
        dropdown.classList.add('show');
        activeIndex = -1;
    }

    function selectKeyword(kw) {
        if (!keywords.includes(kw)) {
            keywords.push(kw);
            renderTags();
            updateHiddenInput();
        }
        input.value = '';
        dropdown.classList.remove('show');
    }

    // Input event - filter keywords
    input.addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        if (val.length === 0) {
            dropdown.classList.remove('show');
            return;
        }
        const filtered = allKeywords.filter(kw => 
            kw.toLowerCase().includes(val) && !keywords.includes(kw)
        );
        showDropdown(filtered);
    });

    // Keyboard navigation
    input.addEventListener('keydown', function(e) {
        const items = dropdown.querySelectorAll('.keyword-dropdown-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = (activeIndex + 1) % items.length;
            items.forEach((item, i) => {
                item.classList.toggle('active', i === activeIndex);
            });
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = (activeIndex - 1 + items.length) % items.length;
            items.forEach((item, i) => {
                item.classList.toggle('active', i === activeIndex);
            });
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && items[activeIndex]) {
                items[activeIndex].click();
            } else {
                const val = this.value.trim();
                if (val && !keywords.includes(val)) {
                    keywords.push(val);
                    renderTags();
                    updateHiddenInput();
                    this.value = '';
                }
            }
            dropdown.classList.remove('show');
        } else if (e.key === 'Escape') {
            dropdown.classList.remove('show');
        }
    });

    // Remove keyword
    tagsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-tag')) {
            const kwToRemove = e.target.getAttribute('data-kw');
            keywords = keywords.filter(k => k !== kwToRemove);
            renderTags();
            updateHiddenInput();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });

    // Load existing keywords
    const existing = <?php echo json_encode(old('keywords', $keywordsData ?? []), 512) ?>;
    if (existing && existing.length > 0) {
        keywords = existing;
        renderTags();
        updateHiddenInput();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/linking/keywords.blade.php ENDPATH**/ ?>