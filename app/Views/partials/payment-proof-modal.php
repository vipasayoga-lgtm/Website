<!-- =========================================================================
     Vipasa Yoga — Payment Proof In-Page Lightbox Modal
     Responsive across Mobile Phones, Laptops, and Mac Retina screens.
     ========================================================================= -->
<style>
@keyframes vy-spin {
    to { transform: rotate(360deg); }
}

#payment-proof-modal {
    position: fixed;
    inset: 0;
    z-index: 999999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(11, 19, 43, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    opacity: 0;
    transition: opacity 0.22s ease;
}

#payment-proof-modal.is-active {
    display: flex;
    opacity: 1;
}

#payment-proof-modal .proof-modal-card {
    position: relative;
    background: #ffffff;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    max-width: 680px;
    width: 100%;
    max-height: 92vh;
    overflow: hidden;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.55), 0 0 1px 1px rgba(255, 255, 255, 0.15);
    display: flex;
    flex-direction: column;
    transform: scale(0.95) translateY(10px);
    transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
}

#payment-proof-modal.is-active .proof-modal-card {
    transform: scale(1) translateY(0);
}

.proof-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    flex-shrink: 0;
}

.proof-modal-close-x {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid #CBD5E1;
    background: #FFFFFF;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    flex-shrink: 0;
    padding: 0;
}

.proof-modal-close-x:hover,
.proof-modal-close-x:focus-visible {
    background: #FEE2E2;
    border-color: #FCA5A5;
    color: #DC2626;
    transform: rotate(90deg) scale(1.08);
}

.proof-modal-viewer {
    position: relative;
    padding: 16px;
    background: #0B132B;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    max-height: 66vh;
    overflow: auto;
    -webkit-overflow-scrolling: touch;
}

.proof-modal-viewer::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.proof-modal-viewer::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.25);
    border-radius: 999px;
}
.proof-modal-viewer::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.45);
}

#proof-modal-img {
    max-width: 100%;
    max-height: calc(66vh - 32px);
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6);
    display: block;
    margin: 0 auto;
    transition: opacity 0.2s ease;
}

.proof-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 14px 20px;
    background: #F8FAFC;
    border-top: 1px solid #E2E8F0;
    flex-shrink: 0;
}

/* Responsive across Phone and Tablet Views (<= 768px) */
@media (max-width: 768px) {
    #payment-proof-modal {
        padding: 8px;
        align-items: flex-end;
    }
    #payment-proof-modal .proof-modal-card {
        max-width: 100% !important;
        width: 100% !important;
        border-radius: 20px 20px 12px 12px !important;
        max-height: 94vh !important;
    }
    .proof-modal-header {
        padding: 12px 16px !important;
    }
    .proof-modal-close-x {
        width: 40px !important;
        height: 40px !important;
    }
    .proof-modal-viewer {
        padding: 12px 8px !important;
        min-height: 240px !important;
        max-height: 60vh !important;
    }
    #proof-modal-img {
        max-height: calc(60vh - 24px) !important;
        border-radius: 8px !important;
    }
    .proof-modal-footer {
        padding: 12px 16px !important;
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .proof-modal-footer .footer-actions {
        display: flex !important;
        width: 100% !important;
        gap: 8px !important;
    }
    .proof-modal-footer .footer-actions .btn {
        flex: 1 1 auto !important;
        text-align: center !important;
        justify-content: center !important;
    }
}

/* Laptop View Enhancement (1024px - 1439px) */
@media (min-width: 1024px) {
    #payment-proof-modal .proof-modal-card {
        max-width: 740px;
    }
    .proof-modal-viewer {
        max-height: 68vh;
    }
    #proof-modal-img {
        max-height: calc(68vh - 32px);
    }
}

/* Mac Retina & High-Res Screens (1440px+) */
@media (min-width: 1440px) {
    #payment-proof-modal .proof-modal-card {
        max-width: 800px;
    }
    .proof-modal-viewer {
        max-height: 70vh;
    }
    #proof-modal-img {
        max-height: calc(70vh - 32px);
    }
}
</style>

<div id="payment-proof-modal" role="dialog" aria-modal="true" aria-labelledby="proof-modal-title">
    <div class="proof-modal-card">
        <!-- Header with Title, Details & Prominent Close 'X' Button -->
        <div class="proof-modal-header">
            <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                <span style="display:grid;place-items:center;width:38px;height:38px;border-radius:10px;background:#EFF6FF;color:#2563EB;flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </span>
                <div style="min-width:0;">
                    <h3 id="proof-modal-title" style="margin:0;font-size:1.05rem;font-weight:700;color:#0F172A;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Payment Proof Verification</h3>
                    <p id="proof-modal-subtitle" style="margin:2px 0 0;font-size:0.75rem;color:#64748B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Client payment submission screenshot</p>
                </div>
            </div>

            <!-- Prominent Close 'X' Icon Button -->
            <button type="button" id="proof-modal-close-btn" class="proof-modal-close-x" title="Close (Esc)" aria-label="Close dialog">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Viewer Body -->
        <div class="proof-modal-viewer">
            <div id="proof-modal-spinner" style="display:none;position:absolute;color:#94A3B8;font-size:0.85rem;align-items:center;gap:8px;z-index:2;">
                <span style="display:inline-block;width:22px;height:22px;border:2.5px solid rgba(56,189,248,0.3);border-top-color:#38BDF8;border-radius:50%;animation:vy-spin 0.75s linear infinite;"></span>
                <span>Loading proof image...</span>
            </div>
            <div id="proof-modal-error" style="display:none;position:absolute;color:#FCA5A5;font-size:0.85rem;text-align:center;padding:16px;z-index:2;">
                <p style="margin:0 0 6px;font-weight:600;">Image not found or could not be loaded.</p>
                <span style="font-size:0.75rem;color:#94A3B8;">The proof file might not exist in the upload directory.</span>
            </div>
            <img id="proof-modal-img" src="" alt="Payment Proof" />
        </div>

        <!-- Footer -->
        <div class="proof-modal-footer">
            <div id="proof-modal-meta" style="font-size:0.82rem;color:#475569;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                <span id="proof-meta-client" style="font-weight:600;color:#0F172A;"></span>
                <span id="proof-meta-amount" style="font-weight:700;color:#059669;background:#DCFCE7;padding:3px 9px;border-radius:12px;font-size:0.75rem;"></span>
            </div>

            <div class="footer-actions" style="display:flex;align-items:center;gap:8px;">
                <a id="proof-modal-download-link" href="#" target="_blank" rel="noopener" class="btn btn-sm btn-secondary" style="font-size:0.8rem;padding:6px 14px;border-radius:8px;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    <span>Open Full Image</span>
                </a>
                <button type="button" id="proof-modal-footer-close" class="btn btn-sm btn-primary" style="font-size:0.8rem;padding:6px 16px;border-radius:8px;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('payment-proof-modal');
    if (!modal) return;

    const modalImg = document.getElementById('proof-modal-img');
    const modalTitle = document.getElementById('proof-modal-title');
    const modalSub = document.getElementById('proof-modal-subtitle');
    const modalClient = document.getElementById('proof-meta-client');
    const modalAmount = document.getElementById('proof-meta-amount');
    const downloadLink = document.getElementById('proof-modal-download-link');
    const closeBtn = document.getElementById('proof-modal-close-btn');
    const footerCloseBtn = document.getElementById('proof-modal-footer-close');
    const modalSpinner = document.getElementById('proof-modal-spinner');
    const modalError = document.getElementById('proof-modal-error');

    function openModal(imgSrc, ref = '', client = '', amount = '') {
        if (!imgSrc) return;

        if (modalError) modalError.style.display = 'none';
        if (modalSpinner) modalSpinner.style.display = 'flex';
        modalImg.style.opacity = '0';
        modalImg.style.display = 'block';

        modalImg.onload = () => {
            if (modalSpinner) modalSpinner.style.display = 'none';
            modalImg.style.opacity = '1';
        };
        modalImg.onerror = () => {
            if (modalSpinner) modalSpinner.style.display = 'none';
            if (modalError) modalError.style.display = 'block';
            modalImg.style.display = 'none';
        };
        modalImg.src = imgSrc;

        if (downloadLink) downloadLink.href = imgSrc;
        if (modalTitle) modalTitle.textContent = ref ? `Payment Proof — ${ref}` : 'Payment Proof Verification';
        if (modalSub) modalSub.textContent = client ? `Client: ${client}` : 'Client payment submission screenshot';
        if (modalClient) modalClient.textContent = client ? `Client: ${client}` : (ref ? `Ref: ${ref}` : '');
        
        if (modalAmount) {
            if (amount) {
                modalAmount.textContent = amount;
                modalAmount.style.display = 'inline-block';
            } else {
                modalAmount.style.display = 'none';
            }
        }

        modal.style.display = 'flex';
        requestAnimationFrame(() => {
            modal.classList.add('is-active');
            closeBtn?.focus();
        });
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('is-active');
        setTimeout(() => {
            modal.style.display = 'none';
            modalImg.src = '';
            if (modalError) modalError.style.display = 'none';
            if (modalSpinner) modalSpinner.style.display = 'none';
        }, 220);
        document.body.style.overflow = '';
    }

    closeBtn?.addEventListener('click', closeModal);
    footerCloseBtn?.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-active')) {
            closeModal();
        }
    });

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.js-view-proof-modal-btn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            const imgSrc = btn.dataset.img || btn.getAttribute('href');
            const ref = btn.dataset.ref || '';
            const client = btn.dataset.client || '';
            const amount = btn.dataset.amount || '';
            openModal(imgSrc, ref, client, amount);
        }
    });
})();
</script>
