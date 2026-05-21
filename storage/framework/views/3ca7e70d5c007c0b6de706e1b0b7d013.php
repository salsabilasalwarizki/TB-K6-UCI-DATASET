
<?php $__env->startSection('title', 'Donation Policy - UCI Machine Learning Repository'); ?>

<?php $__env->startSection('content'); ?>
<div class="policy-container">
    <h1>Donation Policy</h1>
    
    <p>Thank you for considering donating a dataset to the UCI Machine Learning Repository! Through donating a dataset, you are helping keep machine learning a strong and vital research area.</p>
    
    <h3>Before donating a dataset, please read the IMPORTANT information below:</h3>
    
    <ol>
        <li>
            You must have explicit permission to make the dataset publicly available. If you are not the original dataset collector, the original dataset collector should be aware that you are donating the dataset to UCI and provide their consent.
        </li>
        <li>
            If your dataset contains Personally Identifiable Information (PII), this information should be removed prior to donation, such that no individuals can be identified through your dataset.
        </li>
        <li>
            Datasets approved to be in the repository will be assigned a Digital Object Identifier (DOI) if they do not already possess one. DOIs allow for "persistent and actionable identification" of datasets, which is an important component of reproducible research. For more information on DOIs, please read more in the <a href="https://www.doi.org/handbook_2000/introduction.html" target="_blank" rel="noopener">DOI Handbook</a>.
        </li>
        <li>
            Datasets will be licensed under a Creative Commons Attribution 4.0 International license (CC BY 4.0) which allows for the sharing and adaptation of the datasets for any purpose, provided that the appropriate credit is given (see Citation Policy). For more information on the CC BY 4.0 license, please read more in the <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">license deed</a>.
        </li>
    </ol>
    
    <div class="policy-contact">
        <p><strong>For questions, please email <a href="mailto:ml-repository@ics.uci.edu">ml-repository@ics.uci.edu</a></strong></p>
    </div>
    
    <hr style="border-color: #e0e0e0; margin: 2rem 0;">
    
    <!-- Consent Section -->
    <div class="consent-section">
        <h3>Consent to DOI and CC 4.0</h3>
        
        <p>
            By clicking yes below, I am agreeing to the inclusion of the external dataset in the UCI Machine Learning Repository. I understand that this means that my dataset will be publicly available under the CC BY 4.0 license and will be assigned a DOI if one has not already been assigned.
        </p>
        
        <?php if(auth()->guard()->check()): ?>
    <!-- User sudah login -->
    <form action="<?php echo e(route('contribute.metadata')); ?>" method="GET" class="consent-form">
        <input type="hidden" name="agreed" value="1">
        <button type="submit" class="btn btn-agree">
            <i class="bi bi-check-circle me-2"></i>I AGREE
        </button>
    </form>
<?php else: ?>
    <!-- User belum login -->
    <div class="login-prompt">
        <p class="text-muted mb-3">You must be logged in to contribute a dataset.</p>
        <div class="d-flex gap-3 flex-wrap">
            <a href="<?php echo e(route('login')); ?>" class="btn btn-agree">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login to Agree & Contribute
            </a>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </a>
        </div>
    </div>
<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .policy-container {
        max-width: 850px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
    }
    
    .policy-container h1 {
        color: var(--uci-blue);
        font-weight: 700;
        font-size: 2.2rem;
        margin-bottom: 1.5rem;
    }
    
    .policy-container h3 {
        color: var(--uci-blue);
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .policy-container p {
        color: #444;
        line-height: 1.8;
        font-size: 0.95rem;
    }
    
    .policy-container ol {
        padding-left: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .policy-container ol li {
        margin-bottom: 1.25rem;
        line-height: 1.8;
        color: #555;
        font-size: 0.95rem;
    }
    
    .policy-container ol li a {
        color: var(--uci-blue);
        text-decoration: underline;
    }
    
    .policy-contact {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e0e0e0;
    }
    
    .policy-contact a {
        color: var(--uci-blue);
    }
    
    /* Consent Section */
    .consent-section {
        padding-top: 1rem;
    }
    
    .consent-section h3 {
        margin-top: 0;
    }
    
    .consent-section > p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }
    
    .btn-agree {
        background-color: var(--uci-yellow);
        color: #000;
        font-weight: 700;
        padding: 0.85rem 3rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-agree:hover {
        background-color: #ffc300;
        color: #000;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 214, 10, 0.4);
    }
    
    .login-prompt {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem 2rem;
        margin-top: 1rem;
    }
    
    .btn-outline-primary {
        border-color: var(--uci-blue);
        color: var(--uci-blue);
        font-weight: 600;
        padding: 0.85rem 2rem;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    
    .btn-outline-primary:hover {
        background-color: var(--uci-blue);
        color: white;
    }
    
    @media (max-width: 768px) {
        .policy-container {
            padding: 1.5rem 1rem;
        }
        
        .policy-container h1 {
            font-size: 1.75rem;
        }
        
        .btn-agree {
            width: 100%;
            padding: 0.85rem 1.5rem;
        }
        
        .login-prompt {
            padding: 1rem;
        }
        
        .d-flex.gap-3.flex-wrap > .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Downloads\tesdataset-app (4)\tesdataset-app (3)\TB-K6-UCI-DATASET\resources\views/contribute/policy.blade.php ENDPATH**/ ?>