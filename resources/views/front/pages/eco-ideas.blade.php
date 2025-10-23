@extends('layouts.front')

@section('title', 'Eco Ideas Hub - Discover & Collaborate')

@push('styles')
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

/* FORCE DARK NAVBAR TEXT */
.navbar-nav > li > a,
.navbar-brand,
.attr-nav > ul > li > a {
    color: #2c3e50 !important;
    text-shadow: none !important;
}

/* HEADER Z-INDEX FIX */
header,
header.navbar,
.navbar-sticky,
.navbar-fixed {
    z-index: 1000 !important;
    position: fixed !important;
    top: 0 !important;
    width: 100% !important;
}

/* FOOTER SPACING */
footer {
    margin-top: 60px;
    position: relative;
    z-index: 10;
}

/* MAIN CONTAINER */
.eco-hub-wrapper {
    display: flex;
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 30%, #ecfdf5 60%, #d1fae5 100%);
    padding: 120px 15px 80px;
    position: relative;
    overflow-x: hidden;
    max-width: 1600px;
    margin-left: auto;
    margin-right: auto;
}

/* SIDEBAR */
.eco-sidebar {
    width: 240px;
    position: sticky;
    left: 0;
    top: 100px;
    height: calc(100vh - 120px);
    align-self: flex-start;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(229, 231, 235, 0.8);
    padding: 14px 12px;
    overflow-y: auto;
    z-index: 40;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(16, 185, 129, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
    border-radius: 16px;
    margin-top: 20px;
}

.eco-sidebar::-webkit-scrollbar { width: 6px; }
.eco-sidebar::-webkit-scrollbar-track { background: #f1f1f1; }
.eco-sidebar::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }

/* Profile Card */
.profile-card {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 14px;
    padding: 12px;
    color: white;
    margin-bottom: 12px;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.25), 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card:hover {
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3), 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.profile-card::before {
    content: '';
    position: absolute;
    top: -60%;
    right: -35%;
    width: 180px;
    height: 180px;
    background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, transparent 70%);
    border-radius: 50%;
}

.profile-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    margin-bottom: 6px;
    object-fit: cover;
    position: relative;
    z-index: 1;
}

.profile-name {
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 2px;
    position: relative;
    z-index: 1;
}

.profile-role {
    font-size: 11px;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

/* Quick Stats */
.quick-stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 12px;
}

.stat-mini {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    padding: 8px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 1px 3px rgba(16, 185, 129, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 3px solid transparent;
    border: 1px solid rgba(229, 231, 235, 0.5);
}

.stat-mini:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12), 0 4px 12px rgba(16, 185, 129, 0.15);
    border-left-color: #10b981;
}

.stat-mini-number {
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 2px;
}

.stat-mini-label {
    font-size: 8px;
    color: #6b7280;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.2px;
}

/* Navigation */
.sidebar-nav {
    list-style: none;
    margin-bottom: 12px;
}

.nav-item-sidebar {
    margin-bottom: 4px;
}

.nav-link-sidebar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 7px 10px;
    background: transparent;
    border-radius: 10px;
    text-decoration: none;
    color: #6b7280;
    font-weight: 600;
    font-size: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: 1.5px solid transparent;
    position: relative;
    overflow: hidden;
}

.nav-link-sidebar::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 3px;
    height: 100%;
    background: #10b981;
    transform: scaleY(0);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-link-sidebar:hover {
    background: linear-gradient(90deg, rgba(240, 253, 244, 0.8) 0%, rgba(240, 253, 244, 0.4) 100%);
    color: #059669;
    transform: translateX(4px);
    border-color: rgba(209, 250, 229, 0.6);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
}

.nav-link-sidebar:hover::before {
    transform: scaleY(1);
}

.nav-link-sidebar.active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-color: #10b981;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3), inset 0 1px 2px rgba(255, 255, 255, 0.2);
}

.nav-link-sidebar.active::before {
    transform: scaleY(1);
    background: rgba(255, 255, 255, 0.3);
}

.nav-icon {
    width: 16px;
    text-align: center;
    margin-right: 8px;
    font-size: 13px;
}

.nav-badge {
    background: #ef4444;
    color: white;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 8px;
    min-width: 18px;
    text-align: center;
}

.nav-link-sidebar.active .nav-badge {
    background: rgba(255,255,255,0.35);
    color: white;
}

/* Create Button */
.create-btn-sidebar {
    width: 100%;
    padding: 8px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3), inset 0 1px 2px rgba(255, 255, 255, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    position: relative;
    overflow: hidden;
}

.create-btn-sidebar::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.create-btn-sidebar:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4), inset 0 1px 2px rgba(255, 255, 255, 0.3);
}

.create-btn-sidebar:hover::before {
    left: 100%;
}

.create-btn-sidebar:active {
    transform: translateY(-1px) scale(0.98);
}

/* MAIN CONTENT */
.main-content-area {
    flex: 1;
    padding: 20px;
    min-height: calc(100vh - 160px);
    overflow-x: hidden;
    margin-top: 20px;
}

/* Filters */
.filters-bar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 18px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06), 0 2px 8px rgba(16, 185, 129, 0.05);
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    border: 1px solid rgba(229, 231, 235, 0.6);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.filters-bar:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.08), 0 3px 10px rgba(16, 185, 129, 0.08);
}

.search-input {
    flex: 1;
    min-width: 200px;
    padding: 8px 12px 8px 32px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 12px;
    transition: all 0.25s ease;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%2310b981" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>');
    background-repeat: no-repeat;
    background-position: 10px center;
    background-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15), 0 4px 12px rgba(16, 185, 129, 0.1);
    transform: translateY(-1px);
}

.filter-select {
    padding: 8px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 12px;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    font-weight: 600;
    color: #4b5563;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.filter-select:hover {
    border-color: #10b981;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
}

.filter-select:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15), 0 4px 12px rgba(16, 185, 129, 0.1);
    transform: translateY(-1px);
}

/* Projects Grid */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 18px;
}

.project-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 1px 3px rgba(16, 185, 129, 0.05);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
    border: 1px solid rgba(240, 240, 240, 0.8);
}

.project-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #10b981 0%, #3b82f6 50%, #f59e0b 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.project-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12), 0 4px 16px rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.4);
}

.project-card:hover::before {
    opacity: 1;
}

.project-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    opacity: 0;
    transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.project-card:hover::before {
    opacity: 1;
}

.project-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
    background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
}

.project-body {
    padding: 14px;
}

.project-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 10px;
    gap: 10px;
}

.project-title {
    font-size: 15px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 5px;
    line-height: 1.3;
}

.project-author {
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 10px;
}

.status-badge {
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

.status-recruiting { 
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); 
    color: #1e40af;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
}

.status-in_progress { 
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); 
    color: #065f46;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15);
}

.status-completed { 
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
    color: #92400e;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
}

.status-verified { 
    background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); 
    color: #5b21b6;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.15);
}

.project-desc {
    font-size: 12px;
    color: #4b5563;
    line-height: 1.5;
    margin-bottom: 10px;
}

.project-meta {
    display: flex;
    gap: 12px;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
    font-size: 12px;
    color: #6b7280;
    font-weight: 600;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #6b7280;
}

.recruiting-badge {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 750;
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    50% { transform: scale(1.04); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-icon {
    font-size: 56px;
    color: #d1d5db;
    margin-bottom: 14px;
}

.empty-title {
    font-size: 20px;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 6px;
}

.empty-text {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 18px;
}

/* Section Headers */
.section-header {
    margin-bottom: 16px;
}

.section-title {
    font-size: 20px;
    font-weight: 800;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    font-size: 18px;
}

.meta-item i {
    color: #10b981;
    font-size: 12px;
}

/* MODAL STYLES */
.eco-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    z-index: 10000;
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 2px solid #f0f0f0;
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border-radius: 16px 16px 0 0;
}

.modal-header h2 {
    font-size: 22px;
    font-weight: 800;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header i {
    color: #10b981;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.25s ease;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.modal-close:hover {
    background: #fee;
    color: #ef4444;
}

.modal-body {
    padding: 24px;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 6px;
}

.form-control-modal {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    transition: all 0.25s ease;
    font-family: inherit;
}

.form-control-modal:focus {
    outline: none;
    border-color: #10b981;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
}

/* AI Suggestion Button */
.team-requirements-enhancer-wrapper {
    position: relative;
}

/* Custom Number Input with Beautiful Visible Arrows */
input[type="number"]#team_size_needed {
    padding-right: 8px !important;
}

input[type="number"]#team_size_needed::-webkit-inner-spin-button,
input[type="number"]#team_size_needed::-webkit-outer-spin-button {
    opacity: 1 !important;
    cursor: pointer;
    height: 50px;
}

.btn-ai-suggest {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
    z-index: 10;
}

.btn-ai-suggest:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
}

.btn-ai-suggest:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-ai-suggest i {
    margin-right: 6px;
}

.team-requirements-enhancer-wrapper textarea {
    padding-right: 140px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 2px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background: #fafafa;
    border-radius: 0 0 16px 16px;
}

.btn-primary-modal {
    padding: 10px 22px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-primary-modal:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-secondary-modal {
    padding: 10px 22px;
    background: white;
    color: #6b7280;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-secondary-modal:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        max-height: 95vh;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .modal-header h2 {
        font-size: 18px;
    }
}

.mobile-menu-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    z-index: 500;
    display: none;
}

@media (max-width: 1024px) {
    .eco-hub-wrapper {
        padding-left: 0;
        padding-right: 0;
        margin-top: 80px;
    }
    
    .mobile-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 500;
    }
    
    .eco-sidebar {
        position: fixed;
        left: -240px;
        top: 80px;
        z-index: 900;
        height: calc(100vh - 80px);
        margin-top: 0;
        border-radius: 0;
        width: 240px;
    }
    
    .eco-sidebar.mobile-open {
        left: 0;
    }
    
    .main-content-area {
        padding: 15px;
        margin-top: 10px;
    }
    
    .projects-grid {
        grid-template-columns: 1fr;
    }
}

.content-section {
    display: none;
}

.content-section.active {
    display: block;
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { 
        opacity: 0; 
        transform: translateY(20px);
    }
    to { 
        opacity: 1; 
        transform: translateY(0);
    }
}

.project-card {
    animation: fadeInUp 0.5s ease-out backwards;
}

.project-card:nth-child(1) { animation-delay: 0.05s; }
.project-card:nth-child(2) { animation-delay: 0.1s; }
.project-card:nth-child(3) { animation-delay: 0.15s; }
.project-card:nth-child(4) { animation-delay: 0.2s; }
.project-card:nth-child(5) { animation-delay: 0.25s; }
.project-card:nth-child(6) { animation-delay: 0.3s; }
.project-card:nth-child(n+7) { animation-delay: 0.35s; }
</style>
@endpush

@section('content')
<div class="eco-hub-wrapper">
    <!-- SIDEBAR -->
    <aside class="eco-sidebar" id="ecoSidebar">
        @auth
        <!-- Profile Card -->
        <div class="profile-card">
            <img src="{{ auth()->user()->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=10b981&color=fff' }}" 
                 alt="{{ auth()->user()->name }}" 
                 class="profile-avatar">
            <div class="profile-name">{{ auth()->user()->name }}</div>
            <div class="profile-role">🌱 Eco Warrior</div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats-grid">
            <div class="stat-mini" style="border-left-color: #f59e0b;">
                <div class="stat-mini-number" style="color: #f59e0b;">{{ $myProjects->count() }}</div>
                <div class="stat-mini-label">My Projects</div>
            </div>
            <div class="stat-mini" style="border-left-color: #3b82f6;">
                <div class="stat-mini-number" style="color: #3b82f6;">{{ $joinedProjects->count() }}</div>
                <div class="stat-mini-label">Joined</div>
            </div>
            <div class="stat-mini" style="border-left-color: #10b981;">
                <div class="stat-mini-number" style="color: #10b981;">{{ $ideas->count() }}</div>
                <div class="stat-mini-label">All Ideas</div>
            </div>
            <div class="stat-mini" style="border-left-color: #8b5cf6;">
                <div class="stat-mini-number" style="color: #8b5cf6;">{{ $myApplications->count() }}</div>
                <div class="stat-mini-label">Applications</div>
            </div>
        </div>

        <!-- Navigation -->
        <ul class="sidebar-nav">
            <li class="nav-item-sidebar">
                <a class="nav-link-sidebar active" data-section="discover">
                    <span><i class="fas fa-compass nav-icon"></i>Discover</span>
                </a>
            </li>
            <li class="nav-item-sidebar">
                <a class="nav-link-sidebar" data-section="myprojects">
                    <span><i class="fas fa-crown nav-icon"></i>My Projects</span>
                    @if($myProjects->count() > 0)
                        <span class="nav-badge">{{ $myProjects->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item-sidebar">
                <a class="nav-link-sidebar" data-section="joined">
                    <span><i class="fas fa-users nav-icon"></i>Joined</span>
                    @if($joinedProjects->count() > 0)
                        <span class="nav-badge">{{ $joinedProjects->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item-sidebar">
                <a class="nav-link-sidebar" data-section="applications">
                    <span><i class="fas fa-file-alt nav-icon"></i>Applications</span>
                    @if($myApplications->where('status', 'pending')->count() > 0)
                        <span class="nav-badge">{{ $myApplications->where('status', 'pending')->count() }}</span>
                    @endif
                </a>
            </li>
        </ul>

        <!-- Create Button -->
        <button class="create-btn-sidebar" onclick="openCreateModal()">
            <i class="fas fa-plus-circle"></i>
            Create Eco Idea
        </button>
        @else
        <!-- Not Logged In -->
        <div style="text-align: center; padding: 50px 20px;">
            <i class="fas fa-lock" style="font-size: 56px; color: #d1d5db; margin-bottom: 18px;"></i>
            <p style="font-size: 16px; color: #6b7280; margin-bottom: 22px; font-weight: 600;">Login to manage your eco projects</p>
            <a href="{{ route('front.login') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 13px 26px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 10px; font-weight: 700; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                <i class="fas fa-sign-in-alt"></i> Login Now
            </a>
        </div>
        @endauth
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content-area">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-left: 4px solid #10b981; animation: slideDown 0.4s ease;">
                <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                <span style="font-weight: 600; font-size: 14px;">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Info Message -->
        @if(session('info'))
            <div class="alert-info" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-left: 4px solid #3b82f6; animation: slideDown 0.4s ease;">
                <i class="fas fa-info-circle" style="font-size: 20px;"></i>
                <span style="font-weight: 600; font-size: 14px;">{{ session('info') }}</span>
            </div>
        @endif

        <!-- DISCOVER SECTION -->
        <div class="content-section active" id="section-discover">
            <!-- Advanced Filters -->
            <div class="filters-bar" style="display: grid; grid-template-columns: 2fr repeat(4, 1fr) auto; gap: 12px; align-items: center;">
                <!-- Search Input -->
                <input type="text" id="searchInput" class="search-input" placeholder="Search projects, creators, keywords..." style="width: 100%;">
                
                <!-- Status Filter -->
                <select id="statusFilter" class="filter-select">
                    <option value="">📊 All Statuses</option>
                    <option value="recruiting">🔵 Recruiting</option>
                    <option value="in_progress">🟢 In Progress</option>
                    <option value="completed">🟡 Completed</option>
                    <option value="verified">✅ Verified</option>
                </select>
                
                <!-- Waste Type Filter -->
                <select id="wasteFilter" class="filter-select">
                    <option value="">♻️ All Types</option>
                    <option value="organic">🌱 Organic</option>
                    <option value="plastic">🧴 Plastic</option>
                    <option value="metal">🔩 Metal</option>
                    <option value="e-waste">💻 E-Waste</option>
                    <option value="paper">📄 Paper</option>
                    <option value="glass">🥃 Glass</option>
                    <option value="textile">👕 Textile</option>
                    <option value="mixed">📦 Mixed</option>
                </select>
                
                <!-- Difficulty Filter -->
                <select id="difficultyFilter" class="filter-select">
                    <option value="">⚡ Difficulty</option>
                    <option value="easy">🟢 Easy</option>
                    <option value="medium">🟡 Medium</option>
                    <option value="hard">🔴 Hard</option>
                </select>
                
                <!-- Sort By -->
                <select id="sortFilter" class="filter-select">
                    <option value="latest">🆕 Latest</option>
                    <option value="oldest">📅 Oldest</option>
                    <option value="popular">❤️ Most Liked</option>
                    <option value="team_size">👥 Team Size</option>
                </select>
                
                <!-- Reset Button -->
                <button id="resetFilters" class="filter-select" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 6px;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(239, 68, 68, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow=''">
                    <i class="fas fa-redo" style="font-size: 12px;"></i> Reset
                </button>
            </div>
            
            <!-- Active Filters Display -->
            <div id="activeFilters" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; min-height: 24px;"></div>

            <!-- Projects Grid -->
            @if($ideas->count() > 0)
                <div class="projects-grid" id="projectsGrid">
                    @foreach($ideas as $idea)
                        <div class="project-card" 
                             data-status="{{ $idea->project_status }}"
                             data-waste="{{ $idea->waste_type }}"
                             data-title="{{ strtolower($idea->title) }}"
                             onclick="window.location.href='{{ route('front.eco-ideas.show', $idea) }}'">
                            @if($idea->image_path)
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="project-image">
                            @else
                                <div class="project-image"></div>
                            @endif
                            
                            <div class="project-body">
                                <div class="project-header">
                                    <div>
                                        <h3 class="project-title">{{ Str::limit($idea->title, 45) }}</h3>
                                        <p class="project-author">By {{ $idea->creator->name }}</p>
                                    </div>
                                    <span class="status-badge status-{{ $idea->project_status }}">
                                        {{ str_replace('_', ' ', $idea->project_status) }}
                                    </span>
                                </div>
                                
                                <p class="project-desc">{{ Str::limit($idea->description, 100) }}</p>
                                
                                <div class="project-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-heart" style="color: #ef4444;"></i>
                                        {{ $idea->upvotes ?? 0 }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users" style="color: #10b981;"></i>
                                        {{ $idea->team()->count() + 1 }}/{{ $idea->team_size_needed ?? 0 }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-recycle" style="color: #3b82f6;"></i>
                                        {{ ucfirst($idea->waste_type) }}
                                    </div>
                                </div>
                                
                                @if($idea->project_status === 'recruiting')
                                    <div class="recruiting-badge">
                                        <i class="fas fa-user-plus"></i>
                                        Recruiting Now!
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-lightbulb"></i></div>
                    <h2 class="empty-title">No Eco Ideas Yet</h2>
                    <p class="empty-text">Be the first to create an innovative eco idea!</p>
                </div>
            @endif
        </div>

        @auth
        <!-- MY PROJECTS SECTION -->
        <div class="content-section" id="section-myprojects">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-crown" style="color: #f59e0b;"></i>
                    My Projects
                </h2>
            </div>

            @if($myProjects->count() > 0)
                <div class="projects-grid">
                    @foreach($myProjects as $project)
                        <div class="project-card" onclick="window.location.href='{{ route('front.eco-ideas.dashboard.manage', $project) }}'">
                            @if($project->image_path)
                                <img src="{{ asset('storage/' . $project->image_path) }}" alt="{{ $project->title }}" class="project-image">
                            @else
                                <div class="project-image"></div>
                            @endif
                            
                            <div class="project-body">
                                <div class="project-header">
                                    <div>
                                        <h3 class="project-title">{{ $project->title }}</h3>
                                        <p class="project-author" style="color: #f59e0b; font-weight: 700;">
                                            <i class="fas fa-crown"></i> You're the Owner
                                        </p>
                                    </div>
                                    <span class="status-badge status-{{ $project->project_status }}">
                                        {{ str_replace('_', ' ', $project->project_status) }}
                                    </span>
                                </div>
                                
                                <div class="project-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        {{ $project->team()->count() + 1 }} members
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-tasks"></i>
                                        {{ $project->tasks()->where('status', 'completed')->count() }}/{{ $project->tasks()->count() }} tasks
                                    </div>
                                    @if($project->applications_count > 0)
                                        <div class="meta-item" style="color: #ef4444; font-weight: 700;">
                                            <i class="fas fa-bell"></i>
                                            {{ $project->applications_count }} pending
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-folder-open"></i></div>
                    <h2 class="empty-title">No Projects Yet</h2>
                    <p class="empty-text">Create your first eco idea!</p>
                </div>
            @endif
        </div>

        <!-- JOINED PROJECTS SECTION -->
        <div class="content-section" id="section-joined">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-users" style="color: #3b82f6;"></i>
                    Projects I've Joined
                </h2>
            </div>

            @if($joinedProjects->count() > 0)
                <div class="projects-grid">
                    @foreach($joinedProjects as $project)
                        <div class="project-card" style="border: 2px solid #3b82f6;" onclick="window.location.href='{{ route('front.eco-ideas.dashboard.manage', $project) }}'">
                            @if($project->image_path)
                                <img src="{{ asset('storage/' . $project->image_path) }}" alt="{{ $project->title }}" class="project-image">
                            @else
                                <div class="project-image"></div>
                            @endif
                            
                            <div class="project-body">
                                <div class="project-header">
                                    <div>
                                        <h3 class="project-title">{{ $project->title }}</h3>
                                        <p class="project-author" style="color: #3b82f6; font-weight: 700;">
                                            <i class="fas fa-user-check"></i> You're a Member
                                        </p>
                                    </div>
                                    <span class="status-badge status-{{ $project->project_status }}">
                                        {{ str_replace('_', ' ', $project->project_status) }}
                                    </span>
                                </div>
                                
                                <p class="project-author" style="margin-bottom: 10px;">Owner: {{ $project->creator->name }}</p>
                                
                                <div class="project-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        {{ $project->team()->count() + 1 }} members
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-tasks"></i>
                                        My tasks: {{ $project->tasks()->where('assigned_to', auth()->id())->where('status', 'completed')->count() }}/{{ $project->tasks()->where('assigned_to', auth()->id())->count() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-users"></i></div>
                    <h2 class="empty-title">No Joined Projects</h2>
                    <p class="empty-text">Explore eco ideas and join teams!</p>
                </div>
            @endif
        </div>

        <!-- APPLICATIONS SECTION -->
        <div class="content-section" id="section-applications">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-file-alt" style="color: #8b5cf6;"></i>
                    My Applications
                </h2>
            </div>

            @if($myApplications->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 18px;">
                    @foreach($myApplications as $application)
                        <div style="background: white; padding: 22px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'">
                            <div style="display: flex; justify-content: space-between; align-items: start; gap: 18px; flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 240px;">
                                    <h3 style="font-size: 19px; font-weight: 800; margin-bottom: 7px; color: #1a202c;">
                                        {{ $application->ecoIdea->title }}
                                    </h3>
                                    <p style="font-size: 13px; color: #6b7280; margin-bottom: 12px;">
                                        <i class="fas fa-user"></i> By {{ $application->ecoIdea->creator->name }} • 
                                        <i class="fas fa-clock"></i> {{ $application->created_at->diffForHumans() }}
                                    </p>
                                    <p style="font-size: 14px; color: #4b5563; line-height: 1.6;">
                                        {{ Str::limit($application->message, 180) }}
                                    </p>
                                </div>
                                
                                <div style="text-align: right;">
                                    @if($application->status === 'pending')
                                        <span style="display: inline-block; padding: 7px 14px; background: #fef3c7; color: #92400e; border-radius: 999px; font-size: 11px; font-weight: 800; margin-bottom: 12px;">
                                            <i class="fas fa-clock"></i> PENDING
                                        </span>
                                    @elseif($application->status === 'accepted')
                                        <span style="display: inline-block; padding: 7px 14px; background: #d1fae5; color: #065f46; border-radius: 999px; font-size: 11px; font-weight: 800; margin-bottom: 12px;">
                                            <i class="fas fa-check-circle"></i> ACCEPTED
                                        </span>
                                    @else
                                        <span style="display: inline-block; padding: 7px 14px; background: #fef2f2; color: #991b1b; border-radius: 999px; font-size: 11px; font-weight: 800; margin-bottom: 12px;">
                                            <i class="fas fa-times-circle"></i> REJECTED
                                        </span>
                                    @endif
                                    
                                    <button onclick="window.location.href='{{ route('front.eco-ideas.show', $application->ecoIdea) }}'" 
                                            style="display: block; width: 100%; padding: 10px 18px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 700; font-size: 13px; transition: all 0.3s ease;">
                                        <i class="fas fa-external-link-alt"></i> View Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-file-alt"></i></div>
                    <h2 class="empty-title">No Applications Yet</h2>
                    <p class="empty-text">Apply to eco-ideas to join teams!</p>
                </div>
            @endif
        </div>
        @endauth
    </main>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="document.getElementById('ecoSidebar').classList.toggle('mobile-open')">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Create Eco Idea Modal -->
    <div id="createModal" class="eco-modal" style="display: none;">
        <div class="modal-overlay" onclick="closeCreateModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-lightbulb"></i> Create New Eco Idea</h2>
                <button class="modal-close" onclick="closeCreateModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createIdeaForm" action="{{ route('front.eco-ideas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" class="form-control-modal" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control-modal" rows="4" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="waste_type">Waste Type *</label>
                            <select id="waste_type" name="waste_type" class="form-control-modal" required>
                                <option value="organic">Organic</option>
                                <option value="plastic">Plastic</option>
                                <option value="metal">Metal</option>
                                <option value="e-waste">E-Waste</option>
                                <option value="paper">Paper</option>
                                <option value="glass">Glass</option>
                                <option value="textile">Textile</option>
                                <option value="mixed">Mixed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="difficulty_level">Difficulty Level *</label>
                            <select id="difficulty_level" name="difficulty_level" class="form-control-modal" required>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="team_size_needed">Total Team Size (including you)</label>
                        <input type="number" id="team_size_needed" name="team_size_needed" class="form-control-modal" min="1" max="20" value="1" step="1" onkeydown="return false" style="font-size: 18px; font-weight: 600; text-align: center;">
                        <small style="display: block; margin-top: 5px; font-size: 11px; color: #6b7280;">
                            <i class="fas fa-user"></i> Use ↑↓ arrows to adjust. Range: 1-20 members (you as the owner)
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="team_requirements">Team Requirements</label>
                        <div class="team-requirements-enhancer-wrapper">
                            <textarea id="team_requirements" name="team_requirements" class="form-control-modal" rows="3" placeholder="Skills needed: coding, design, marketing..."></textarea>
                            <button type="button" id="suggestTeamBtn" class="btn-ai-suggest" title="Suggest with AI">
                                <span class="btn-text">
                                    <i class="fas fa-sparkles"></i> Suggest with AI
                                </span>
                                <span class="btn-loader" style="display:none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="application_description">Application Instructions</label>
                        <textarea id="application_description" name="application_description" class="form-control-modal" rows="3" placeholder="Tell applicants what you're looking for..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Image *</label>
                        <input type="file" id="image" name="image" class="form-control-modal" accept="image/*" required>
                        <small id="imageHelp" style="display: block; margin-top: 5px; font-size: 11px; color: #6b7280;">
                            <i class="fas fa-info-circle"></i> Please upload a project image (JPG, PNG, or GIF)
                        </small>
                    </div>

                    <!-- Copyright Verification Section -->
                    <div id="copyrightSection" style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; border-radius: 8px; margin-top: 15px;">
                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #92400e; font-weight: 600;">
                            <i class="fas fa-shield-alt"></i> <strong>Copyright Verification Required</strong>
                        </p>
                        <p style="margin: 0 0 10px 0; font-size: 11px; color: #92400e;">
                            Before creating your idea, you must verify it's original and not too similar (≥80%) to existing projects.
                        </p>
                        <button type="button" id="verifyOriginality" class="btn-verify-copyright" style="width: 100%; padding: 10px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 13px;">
                            <span class="btn-text-verify">
                                <i class="fas fa-check-circle"></i> Verify Originality with AI
                            </span>
                            <span class="btn-loader-verify" style="display:none;">
                                <i class="fas fa-spinner fa-spin"></i> Checking...
                            </span>
                        </button>
                        <div id="verificationResult" style="margin-top: 10px; display: none;"></div>
                    </div>

                    <!-- Status is now automatic based on team size and task completion -->
                    <div style="background: #e0f2fe; border-left: 4px solid #3b82f6; padding: 12px; border-radius: 8px; margin-top: 10px;">
                        <p style="margin: 0; font-size: 12px; color: #1e40af;">
                            <i class="fas fa-info-circle"></i> <strong>Project Status:</strong> Automatically managed
                        </p>
                        <ul style="margin: 8px 0 0 20px; font-size: 11px; color: #1e40af; line-height: 1.6;">
                            <li>🔵 <strong>Recruiting:</strong> When team is not full</li>
                            <li>🟢 <strong>In Progress:</strong> When team is full</li>
                            <li>🟡 <strong>Completed:</strong> When all tasks are completed</li>
                        </ul>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-modal" onclick="closeCreateModal()">Cancel</button>
                    <button type="submit" id="submitIdeaBtn" class="btn-primary-modal" disabled style="opacity: 0.5; cursor: not-allowed;">
                        <i class="fas fa-check"></i> Create Eco Idea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OLD CONTENT BELOW (to be removed) -->
<div style="display: none;">
        <!-- Advanced Search & Filters - Products Style -->
        <div class="search-filters-container">
            <div class="filters-grid">
                <div class="filter-column">
                    <label class="filter-label">SEARCH</label>
                    <div class="search-wrapper">
                        <input type="text" id="search-input" class="filter-input" placeholder="Search eco ideas...">
                        <i class="fas fa-search search-icon-right"></i>
                    </div>
                </div>

                <div class="filter-column">
                    <label class="filter-label">WASTE TYPE</label>
                    <select id="waste-type-filter" class="filter-input">
                        <option value="all">All Waste Types</option>
                        <option value="organic">Organic</option>
                        <option value="plastic">Plastic</option>
                        <option value="metal">Metal</option>
                        <option value="e-waste">E-Waste</option>
                        <option value="paper">Paper</option>
                        <option value="glass">Glass</option>
                        <option value="textile">Textile</option>
                        <option value="mixed">Mixed</option>
                    </select>
                </div>

                <div class="filter-column">
                    <label class="filter-label">DIFFICULTY</label>
                    <select id="difficulty-filter" class="filter-input">
                        <option value="all">All Difficulties</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="filter-column">
                    <label class="filter-label">STATUS</label>
                    <select id="status-filter" class="filter-input">
                        <option value="all">All Status</option>
                        <option value="idea">Idea</option>
                        <option value="recruiting">Recruiting</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="verified">Verified</option>
                    </select>
                </div>

                <div class="filter-column">
                    <label class="filter-label">SORT BY</label>
                    <select id="sort-by" class="filter-input">
                        <option value="latest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="most-liked">Most Liked</option>
                        <option value="least-liked">Least Liked</option>
                    </select>
                </div>

                <div class="filter-column filter-actions">
                    <button class="reset-btn" id="reset-filters">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <div class="bottom-bar">
                <div class="view-toggle-left">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                <div class="results-count">
                    <span id="results-count">{{ $ideas->count() }}</span> ideas found
                </div>
            </div>
        </div>

        @if($ideas->count() > 0)
            <div class="ideas-grid">
                @foreach($ideas as $idea)
                    <div class="idea-card">
                        <div class="idea-image-wrapper" onclick="window.location.href='{{ route('front.eco-ideas.show', $idea->id) }}'">
                            @if($idea->image_path && file_exists(public_path('storage/' . $idea->image_path)))
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}" class="idea-image">
                            @else
                                <img src="{{ asset('assets/front/img/default-eco.jpg') }}" alt="{{ $idea->title }}" class="idea-image">
                            @endif
                            
                            <div class="idea-badges">
                                <span class="idea-badge badge-waste">{{ ucfirst($idea->waste_type) }}</span>
                                <span class="idea-badge badge-difficulty">{{ ucfirst($idea->difficulty_level) }}</span>
                                @if($idea->project_status === 'verified')
                                    <span class="idea-badge badge-verified"><i class="fas fa-check-circle"></i> Verified</span>
                                @else
                                    <span class="idea-badge badge-status">{{ ucfirst(str_replace('_', ' ', $idea->project_status ?? 'idea')) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="idea-content-wrapper">
                            <div class="idea-header">
                                <img src="{{ $idea->creator->profile_picture_url ?? asset('assets/front/img/default-avatar.jpg') }}" 
                                     alt="{{ $idea->creator->name ?? 'Creator' }}" 
                                     class="idea-avatar"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($idea->creator->name ?? 'U') }}&background=10b981&color=fff'">
                                <div class="idea-creator-info">
                                    <h4>{{ $idea->creator->name ?? 'Unknown Creator' }}</h4>
                                    <span>{{ $idea->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="idea-body">
                                <h3 class="idea-title">{{ $idea->title }}</h3>
                                <p class="idea-description">{{ Str::limit($idea->description, 150) }}</p>
                                
                                <div class="idea-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-heart"></i>
                                        <span>{{ $idea->upvotes ?? 0 }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $idea->team_size_current ?? 1 }}/{{ $idea->team_size_needed ?? 0 }}</span>
                                    </div>
                                    @if($idea->project_status === 'recruiting')
                                        <div class="meta-item">
                                            <i class="fas fa-user-plus"></i>
                                            <span>Recruiting</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="idea-actions">
                            @auth
                                <button onclick="likeIdea({{ $idea->id }}, this)" 
                                        class="action-btn {{ $idea->interactions->where('user_id', auth()->id())->where('type', 'like')->count() > 0 ? 'liked' : '' }}" 
                                        style="flex: 1; border: none;">
                                    <i class="fas fa-heart"></i>
                                    <span class="count">{{ $idea->upvotes ?? 0 }}</span>
                                </button>
                            @else
                                <div class="action-btn" style="flex: 1;">
                                    <i class="fas fa-heart"></i>
                                    <span class="count">{{ $idea->upvotes ?? 0 }}</span>
                                </div>
                            @endauth
                            
                            <a href="{{ route('front.eco-ideas.show', $idea->id) }}" class="action-btn primary">
                                <i class="fas fa-eye"></i>
                                <span>View Details</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-ideas">
                <i class="fas fa-lightbulb"></i>
                <h3>No Eco Ideas Yet</h3>
                <p>Be the first to create an eco idea and inspire others!</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal Functions
    function openCreateModal() {
        document.getElementById('createModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal() {
        document.getElementById('createModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
        }
    });

    // ========== FORM VALIDATION WITH VISUAL FEEDBACK ==========
    const createForm = document.getElementById('createIdeaForm');
    
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            let isValid = true;
            let firstInvalidField = null;

            // Helper function to mark field as invalid
            function markInvalid(fieldId, message) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.style.border = '2px solid #ef4444';
                    field.style.background = '#fef2f2';
                    
                    // Remove existing error message if any
                    const existingError = field.parentElement.querySelector('.error-message');
                    if (existingError) {
                        existingError.remove();
                    }
                    
                    // Add error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.cssText = 'color: #ef4444; font-size: 11px; margin-top: 4px; font-weight: 600;';
                    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
                    field.parentElement.appendChild(errorDiv);
                    
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                    isValid = false;
                }
            }

            // Helper function to clear validation styling
            function clearValidation(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.style.border = '';
                    field.style.background = '';
                    const errorMsg = field.parentElement.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            }

            // Clear all previous validation
            ['title', 'description', 'waste_type', 'difficulty_level', 'image'].forEach(clearValidation);

            // Validate Title
            const title = document.getElementById('title').value.trim();
            if (!title) {
                markInvalid('title', 'Title is required');
            } else if (title.length < 5) {
                markInvalid('title', 'Title must be at least 5 characters');
            }

            // Validate Description
            const description = document.getElementById('description').value.trim();
            if (!description) {
                markInvalid('description', 'Description is required');
            } else if (description.length < 20) {
                markInvalid('description', 'Description must be at least 20 characters');
            }

            // Validate Waste Type (already selected by default, but check anyway)
            const wasteType = document.getElementById('waste_type').value;
            if (!wasteType) {
                markInvalid('waste_type', 'Please select a waste type');
            }

            // Validate Difficulty Level
            const difficultyLevel = document.getElementById('difficulty_level').value;
            if (!difficultyLevel) {
                markInvalid('difficulty_level', 'Please select a difficulty level');
            }

            // Validate Image Upload
            const imageInput = document.getElementById('image');
            if (!imageInput.files || imageInput.files.length === 0) {
                markInvalid('image', 'Please upload a project image');
            } else {
                // Validate file type
                const file = imageInput.files[0];
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    markInvalid('image', 'Please upload a valid image file (JPG, PNG, or GIF)');
                }
                // Validate file size (max 5MB)
                else if (file.size > 5 * 1024 * 1024) {
                    markInvalid('image', 'Image size must be less than 5MB');
                }
            }

            // If validation failed, prevent submission and focus first invalid field
            if (!isValid) {
                e.preventDefault();
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Real-time validation - clear errors as user types
        ['title', 'description', 'waste_type', 'difficulty_level', 'image'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function() {
                    this.style.border = '';
                    this.style.background = '';
                    const errorMsg = this.parentElement.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                });
                
                field.addEventListener('change', function() {
                    this.style.border = '';
                    this.style.background = '';
                    const errorMsg = this.parentElement.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                });
            }
        });
    }

    // Restore active section from localStorage on page load
    const savedSection = localStorage.getItem('ecoIdeasActiveSection') || 'discover';
    document.querySelectorAll('.nav-link-sidebar').forEach(l => l.classList.remove('active'));
    document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
    
    const savedLink = document.querySelector(`[data-section="${savedSection}"]`);
    const savedContent = document.getElementById(`section-${savedSection}`);
    
    if (savedLink && savedContent) {
        savedLink.classList.add('active');
        savedContent.classList.add('active');
    } else {
        // Fallback to discover
        document.querySelector('[data-section="discover"]').classList.add('active');
        document.getElementById('section-discover').classList.add('active');
    }

    // Sidebar Navigation
    document.querySelectorAll('.nav-link-sidebar').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active from all
            document.querySelectorAll('.nav-link-sidebar').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            
            // Add active to clicked
            this.classList.add('active');
            const section = this.getAttribute('data-section');
            document.getElementById('section-' + section).classList.add('active');
            
            // Save active section to localStorage
            localStorage.setItem('ecoIdeasActiveSection', section);
            
            // Close mobile menu
            document.getElementById('ecoSidebar').classList.remove('mobile-open');
        });
    });

    // Enhanced Filter functionality
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const wasteFilter = document.getElementById('wasteFilter');
    const difficultyFilter = document.getElementById('difficultyFilter');
    const sortFilter = document.getElementById('sortFilter');
    const resetBtn = document.getElementById('resetFilters');
    const activeFiltersDiv = document.getElementById('activeFilters');
    
    function updateActiveFilters() {
        const filters = [];
        if (searchInput.value) filters.push({ label: `Search: "${searchInput.value}"`, clear: () => searchInput.value = '' });
        if (statusFilter.value) filters.push({ label: `Status: ${statusFilter.options[statusFilter.selectedIndex].text}`, clear: () => statusFilter.value = '' });
        if (wasteFilter.value) filters.push({ label: `Type: ${wasteFilter.options[wasteFilter.selectedIndex].text}`, clear: () => wasteFilter.value = '' });
        if (difficultyFilter.value) filters.push({ label: `${difficultyFilter.options[difficultyFilter.selectedIndex].text}`, clear: () => difficultyFilter.value = '' });
        
        activeFiltersDiv.innerHTML = filters.map((filter, i) => 
            `<span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);">
                ${filter.label}
                <i class="fas fa-times" style="cursor: pointer; font-size: 10px;" onclick="clearFilter(${i})"></i>
            </span>`
        ).join('');
    }
    
    window.clearFilter = function(index) {
        const filters = [];
        if (searchInput.value) filters.push(() => searchInput.value = '');
        if (statusFilter.value) filters.push(() => statusFilter.value = '');
        if (wasteFilter.value) filters.push(() => wasteFilter.value = '');
        if (difficultyFilter.value) filters.push(() => difficultyFilter.value = '');
        filters[index]();
        filterProjects();
    };
    
    function filterProjects() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const wasteValue = wasteFilter.value;
        const difficultyValue = difficultyFilter.value;
        const sortValue = sortFilter.value;
        
        let cards = Array.from(document.querySelectorAll('#projectsGrid .project-card'));
        
        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            const status = card.getAttribute('data-status');
            const waste = card.getAttribute('data-waste');
            
            const difficulty = card.getAttribute('data-difficulty') || card.querySelector('[data-difficulty]')?.textContent.toLowerCase();
            
            const matchesSearch = title.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesWaste = !wasteValue || waste === wasteValue;
            const matchesDifficulty = !difficultyValue || difficulty === difficultyValue;
            
            if (matchesSearch && matchesStatus && matchesWaste && matchesDifficulty) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Sorting
        const visibleCards = cards.filter(card => card.style.display !== 'none');
        const grid = document.getElementById('projectsGrid');
        
        if (sortValue === 'latest') {
            // Default order - no change needed
        } else if (sortValue === 'oldest') {
            visibleCards.reverse();
        } else if (sortValue === 'popular') {
            visibleCards.sort((a, b) => {
                const likesA = parseInt(a.querySelector('[data-likes]')?.textContent || 0);
                const likesB = parseInt(b.querySelector('[data-likes]')?.textContent || 0);
                return likesB - likesA;
            });
        } else if (sortValue === 'team_size') {
            visibleCards.sort((a, b) => {
                const teamA = parseInt(a.querySelector('[data-team-size]')?.textContent || 0);
                const teamB = parseInt(b.querySelector('[data-team-size]')?.textContent || 0);
                return teamB - teamA;
            });
        }
        
        // Reorder DOM
        visibleCards.forEach(card => grid.appendChild(card));
        
        updateActiveFilters();
    }
    
    // Event Listeners
    if (searchInput) searchInput.addEventListener('input', filterProjects);
    if (statusFilter) statusFilter.addEventListener('change', filterProjects);
    if (wasteFilter) wasteFilter.addEventListener('change', filterProjects);
    if (difficultyFilter) difficultyFilter.addEventListener('change', filterProjects);
    if (sortFilter) sortFilter.addEventListener('change', filterProjects);
    
    // Reset Button
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            wasteFilter.value = '';
            difficultyFilter.value = '';
            sortFilter.value = 'latest';
            filterProjects();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
        initializeViewToggle();
    });

    let allIdeas = [];

    function initializeFilters() {
        // Store all ideas for filtering
        const ideaCards = document.querySelectorAll('.idea-card');
        ideaCards.forEach(card => {
            allIdeas.push({
                element: card,
                title: card.querySelector('.idea-title').textContent.toLowerCase(),
                description: card.querySelector('.idea-description').textContent.toLowerCase(),
                creator: card.querySelector('.idea-creator-info h4').textContent.toLowerCase(),
                wasteType: card.querySelector('.badge-waste').textContent.toLowerCase().trim(),
                difficulty: card.querySelector('.badge-difficulty').textContent.toLowerCase().trim(),
                status: card.querySelector('.badge-status, .badge-verified').textContent.toLowerCase().trim(),
                upvotes: parseInt(card.querySelector('.meta-item .count') ? card.querySelector('.meta-item span').textContent : 0),
                timestamp: card.querySelector('.idea-creator-info span').textContent
            });
        });

        // Search input
        const searchInput = document.getElementById('search-input');
        
        searchInput.addEventListener('input', function() {
            applyFilters();
        });

        // Filter selects
        document.getElementById('waste-type-filter').addEventListener('change', applyFilters);
        document.getElementById('difficulty-filter').addEventListener('change', applyFilters);
        document.getElementById('status-filter').addEventListener('change', applyFilters);
        document.getElementById('sort-by').addEventListener('change', applyFilters);

        // Reset button
        document.getElementById('reset-filters').addEventListener('click', function() {
            searchInput.value = '';
            document.getElementById('waste-type-filter').value = 'all';
            document.getElementById('difficulty-filter').value = 'all';
            document.getElementById('status-filter').value = 'all';
            document.getElementById('sort-by').value = 'latest';
            applyFilters();
        });
    }

    function applyFilters() {
        const searchTerm = document.getElementById('search-input').value.toLowerCase();
        const wasteType = document.getElementById('waste-type-filter').value;
        const difficulty = document.getElementById('difficulty-filter').value;
        const status = document.getElementById('status-filter').value;
        const sortBy = document.getElementById('sort-by').value;

        let filteredIdeas = allIdeas.filter(idea => {
            // Search filter
            const matchesSearch = searchTerm === '' || 
                idea.title.includes(searchTerm) || 
                idea.description.includes(searchTerm) ||
                idea.creator.includes(searchTerm);

            // Waste type filter
            const matchesWaste = wasteType === 'all' || idea.wasteType === wasteType;

            // Difficulty filter
            const matchesDifficulty = difficulty === 'all' || idea.difficulty === difficulty;

            // Status filter
            const matchesStatus = status === 'all' || idea.status.includes(status.replace('_', ' '));

            return matchesSearch && matchesWaste && matchesDifficulty && matchesStatus;
        });

        // Sort filtered ideas
        filteredIdeas.sort((a, b) => {
            if (sortBy === 'most-liked') return b.upvotes - a.upvotes;
            if (sortBy === 'least-liked') return a.upvotes - b.upvotes;
            if (sortBy === 'oldest') return 1; // Keep original order
            return -1; // Latest first (default)
        });

        // Hide all cards
        allIdeas.forEach(idea => {
            idea.element.style.display = 'none';
        });

        // Show filtered cards with animation
        filteredIdeas.forEach((idea, index) => {
            idea.element.style.display = '';
            idea.element.style.animation = 'none';
            setTimeout(() => {
                idea.element.style.animation = `fadeInUp 0.4s ease ${index * 0.05}s forwards`;
            }, 10);
        });

        // Update results count
        document.getElementById('results-count').textContent = filteredIdeas.length;
    }

    function initializeViewToggle() {
        const viewButtons = document.querySelectorAll('.view-btn');
        const ideasContainer = document.querySelector('.ideas-grid');

        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.dataset.view;
                
                // Update active button
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Switch view
                if (view === 'list') {
                    ideasContainer.classList.remove('ideas-grid');
                    ideasContainer.classList.add('ideas-list');
                } else {
                    ideasContainer.classList.remove('ideas-list');
                    ideasContainer.classList.add('ideas-grid');
                }
            });
        });
    }

    function likeIdea(ideaId, buttonElement) {
        fetch(`/eco-ideas/${ideaId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                window.location.href = '/login';
                return;
            }

            // Update the like count
            const countSpan = buttonElement.querySelector('.count');
            countSpan.textContent = data.upvotes;

            // Toggle the liked class
            if (data.liked) {
                buttonElement.classList.add('liked');
            } else {
                buttonElement.classList.remove('liked');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // ========== AI TEAM REQUIREMENTS SUGGESTION ==========
    const teamRequirementsField = document.getElementById('team_requirements');
    const suggestTeamBtn = document.getElementById('suggestTeamBtn');

    if (suggestTeamBtn) {
        suggestTeamBtn.addEventListener('click', async function() {
            // Get form values
            const title = document.getElementById('title')?.value?.trim() || '';
            const description = document.getElementById('description')?.value?.trim() || '';
            const wasteType = document.getElementById('waste_type')?.value || '';
            const difficultyLevel = document.getElementById('difficulty_level')?.value || '';
            const teamSize = document.getElementById('team_size_needed')?.value || null;

            // Validation - silent check
            if (!title || title.length < 3) {
                document.getElementById('title').style.border = '2px solid #ef4444';
                document.getElementById('title').focus();
                setTimeout(() => {
                    document.getElementById('title').style.border = '';
                }, 2000);
                return;
            }

            if (!description || description.length < 10) {
                document.getElementById('description').style.border = '2px solid #ef4444';
                document.getElementById('description').focus();
                setTimeout(() => {
                    document.getElementById('description').style.border = '';
                }, 2000);
                return;
            }

            // Show loading state
            suggestTeamBtn.disabled = true;
            suggestTeamBtn.querySelector('.btn-text').style.display = 'none';
            suggestTeamBtn.querySelector('.btn-loader').style.display = 'inline-block';

            try {
                const response = await fetch('/chatbot/suggest-team-requirements', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        title,
                        description,
                        waste_type: wasteType,
                        difficulty_level: difficultyLevel,
                        team_size: teamSize
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Set suggested team requirements
                    teamRequirementsField.value = data.suggested;

                    // Visual feedback
                    teamRequirementsField.style.background = '#ede9fe';
                    setTimeout(() => {
                        teamRequirementsField.style.background = '';
                    }, 2000);
                } else {
                    // Log error silently
                    console.error('AI Suggestion error:', data.message);
                }
            } catch (error) {
                console.error('AI Suggestion error:', error);
            } finally {
                // Reset button state
                suggestTeamBtn.disabled = false;
                suggestTeamBtn.querySelector('.btn-text').style.display = 'inline-block';
                suggestTeamBtn.querySelector('.btn-loader').style.display = 'none';
            }
        });
    }

    // ========== COPYRIGHT VERIFICATION SYSTEM ==========
    let isVerified = false;
    const verifyBtn = document.getElementById('verifyOriginality');
    const submitBtn = document.getElementById('submitIdeaBtn');
    const verificationResult = document.getElementById('verificationResult');

    if (verifyBtn) {
        verifyBtn.addEventListener('click', async function() {
            const title = document.getElementById('title')?.value?.trim() || '';
            const description = document.getElementById('description')?.value?.trim() || '';

            // Validation
            if (!title || title.length < 5) {
                document.getElementById('title').style.border = '2px solid #ef4444';
                document.getElementById('title').focus();
                setTimeout(() => {
                    document.getElementById('title').style.border = '';
                }, 2000);
                return;
            }

            if (!description || description.length < 20) {
                document.getElementById('description').style.border = '2px solid #ef4444';
                document.getElementById('description').focus();
                setTimeout(() => {
                    document.getElementById('description').style.border = '';
                }, 2000);
                return;
            }

            // Show loading state
            verifyBtn.disabled = true;
            verifyBtn.querySelector('.btn-text-verify').style.display = 'none';
            verifyBtn.querySelector('.btn-loader-verify').style.display = 'inline-block';
            verificationResult.style.display = 'none';

            try {
                const response = await fetch('/chatbot/check-idea-originality', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ title, description })
                });

                const data = await response.json();

                if (data.success && data.is_original) {
                    // PASSED - Original idea
                    isVerified = true;
                    verificationResult.innerHTML = `
                        <div style="background: #d1fae5; border: 2px solid #10b981; border-radius: 8px; padding: 12px;">
                            <p style="margin: 0; color: #065f46; font-weight: 600; font-size: 13px;">
                                <i class="fas fa-check-circle"></i> ✅ Originality Verified!
                            </p>
                            <p style="margin: 5px 0 0 0; color: #047857; font-size: 11px;">
                                Similarity: ${data.similarity_percentage}% - Your idea is original enough to proceed.
                            </p>
                        </div>
                    `;
                    verificationResult.style.display = 'block';
                    
                    // Enable submit button
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    
                    // Update verify button
                    verifyBtn.querySelector('.btn-text-verify').innerHTML = '<i class="fas fa-check-double"></i> Verified ✓';
                    verifyBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    
                } else if (data.success && !data.is_original) {
                    // FAILED - Too similar
                    isVerified = false;
                    const similarTitle = data.similar_idea_title || 'an existing project';
                    verificationResult.innerHTML = `
                        <div style="background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; padding: 12px;">
                            <p style="margin: 0; color: #991b1b; font-weight: 600; font-size: 13px;">
                                <i class="fas fa-times-circle"></i> ❌ Not Original
                            </p>
                            <p style="margin: 5px 0; color: #b91c1c; font-size: 11px;">
                                Similarity: <strong>${data.similarity_percentage}%</strong> (threshold: 80%)
                            </p>
                            <p style="margin: 5px 0 0 0; color: #b91c1c; font-size: 11px;">
                                Your idea is too similar to "<strong>${similarTitle}</strong>". Please create a unique project.
                            </p>
                            ${data.reasoning ? `<p style="margin: 5px 0 0 0; color: #991b1b; font-size: 10px; font-style: italic;">Reason: ${data.reasoning}</p>` : ''}
                        </div>
                    `;
                    verificationResult.style.display = 'block';
                    
                    // Keep submit button disabled
                    submitBtn.disabled = true;
                } else {
                    // Error
                    verificationResult.innerHTML = `
                        <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 12px;">
                            <p style="margin: 0; color: #92400e; font-size: 11px;">
                                <i class="fas fa-exclamation-triangle"></i> ${data.message || 'Could not verify originality. Please try again.'}
                            </p>
                        </div>
                    `;
                    verificationResult.style.display = 'block';
                }
            } catch (error) {
                console.error('Originality check error:', error);
                verificationResult.innerHTML = `
                    <div style="background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; padding: 12px;">
                        <p style="margin: 0; color: #991b1b; font-size: 11px;">
                            <i class="fas fa-exclamation-triangle"></i> Verification failed. Please try again.
                        </p>
                    </div>
                `;
                verificationResult.style.display = 'block';
            } finally {
                // Reset button state
                verifyBtn.disabled = false;
                verifyBtn.querySelector('.btn-text-verify').style.display = 'inline-block';
                verifyBtn.querySelector('.btn-loader-verify').style.display = 'none';
            }
        });
    }

    // Prevent form submission if not verified
    if (createForm) {
        const originalSubmitHandler = createForm.onsubmit;
        createForm.addEventListener('submit', function(e) {
            if (!isVerified) {
                e.preventDefault();
                verificationResult.innerHTML = `
                    <div style="background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; padding: 12px;">
                        <p style="margin: 0; color: #991b1b; font-weight: 600; font-size: 12px;">
                            <i class="fas fa-exclamation-circle"></i> You must verify originality before creating!
                        </p>
                    </div>
                `;
                verificationResult.style.display = 'block';
                verifyBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        });
    }

    // Reset verification when title or description changes
    ['title', 'description'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                if (isVerified) {
                    isVerified = false;
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                    verifyBtn.querySelector('.btn-text-verify').innerHTML = '<i class="fas fa-check-circle"></i> Verify Originality with AI';
                    verifyBtn.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                    verificationResult.innerHTML = `
                        <div style="background: #fef3c7; border: 2px solid #f59e0b; border-radius: 8px; padding: 8px;">
                            <p style="margin: 0; color: #92400e; font-size: 11px;">
                                <i class="fas fa-info-circle"></i> Changes detected. Please re-verify originality.
                            </p>
                        </div>
                    `;
                    verificationResult.style.display = 'block';
                }
            });
        }
    });
</script>
@endpush
