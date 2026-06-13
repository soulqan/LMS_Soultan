document.addEventListener('DOMContentLoaded', () => {
    initCatalogFilters();
    initCatalogMobileFilters();
    initProfileDropdown();
    initMobileMenus();
    initHomeSpotlight();
    initPlayerState();
    initCourseAdminEditor();
    initStarRatingWidget();
    initLessonAdminEditor();
});

function initCatalogMobileFilters() {
    const toggle = document.querySelector('[data-filter-toggle]');
    const panel = document.querySelector('[data-filter-panel]');

    if (!toggle || !panel) {
        return;
    }

    const closePanel = () => {
        panel.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
    };

    toggle.setAttribute('aria-expanded', 'false');

    toggle.addEventListener('click', (event) => {
        event.stopPropagation();

        const isHidden = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !isHidden);
        toggle.setAttribute('aria-expanded', String(isHidden));
    });

    panel.addEventListener('click', (event) => event.stopPropagation());
    document.addEventListener('click', closePanel);
}

function initCatalogFilters() {
    const catalogPage = document.querySelector('[data-catalog-page]');

    if (!catalogPage) {
        return;
    }

    const searchInputs = [...catalogPage.querySelectorAll('[data-course-search]')];
    const categoryButtons = [...catalogPage.querySelectorAll('[data-category-button]')];
    const levelFilters = [...catalogPage.querySelectorAll('[data-level-filter]')];
    const cards = [...catalogPage.querySelectorAll('[data-course-card]')];

    let activeCategory = 'all';

    const setCategoryState = () => {
        categoryButtons.forEach((button) => {
            const isActive = (button.dataset.categoryButton ?? 'all') === activeCategory;

            button.classList.toggle('bg-blue-50', isActive);
            button.classList.toggle('text-blue-600', isActive);
            button.classList.toggle('text-slate-700', !isActive);
            button.classList.toggle('hover:bg-slate-50', !isActive);
        });
    };

    const applyFilters = () => {
        const query = (searchInputs[0]?.value ?? '').trim().toLowerCase();
        const selectedLevels = levelFilters.filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);

        cards.forEach((card) => {
            const title = (card.dataset.courseTitle ?? '').toLowerCase();
            const category = card.dataset.courseCategory ?? 'all';
            const level = card.dataset.courseLevel ?? '';

            const matchesSearch = !query || title.includes(query);
            const matchesCategory = activeCategory === 'all' || category === activeCategory;
            const matchesLevel = selectedLevels.length === 0 || selectedLevels.includes(level);

            card.classList.toggle('hidden', !(matchesSearch && matchesCategory && matchesLevel));
        });
    };

    searchInputs.forEach((input) => {
        input.addEventListener('input', () => {
            const value = input.value;

            searchInputs.forEach((field) => {
                if (field !== input) {
                    field.value = value;
                }
            });

            applyFilters();
        });
    });

    categoryButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activeCategory = button.dataset.categoryButton ?? 'all';
            setCategoryState();
            applyFilters();
        });
    });

    levelFilters.forEach((checkbox) => {
        checkbox.addEventListener('change', applyFilters);
    });

    setCategoryState();
    applyFilters();
}

function initProfileDropdown() {
    const toggles = [...document.querySelectorAll('[data-profile-toggle]')];

    if (toggles.length === 0) {
        return;
    }

    const closeMenus = () => {
        toggles.forEach((toggle) => {
            const menu = toggle.parentElement?.querySelector('[data-profile-menu]');

            if (menu) {
                menu.classList.add('hidden');
            }

            toggle.setAttribute('aria-expanded', 'false');
        });
    };

    toggles.forEach((toggle) => {
        const menu = toggle.parentElement?.querySelector('[data-profile-menu]');

        if (!menu) {
            return;
        }

        toggle.setAttribute('aria-expanded', 'false');

        toggle.addEventListener('click', (event) => {
            event.stopPropagation();

            const isHidden = menu.classList.contains('hidden');
            closeMenus();
            menu.classList.toggle('hidden', !isHidden);
            toggle.setAttribute('aria-expanded', String(isHidden));
        });

        menu.addEventListener('click', (event) => event.stopPropagation());
    });

    document.addEventListener('click', closeMenus);
}

function initMobileMenus() {
    const toggles = [...document.querySelectorAll('[data-mobile-menu-toggle]')];

    if (toggles.length === 0) {
        return;
    }

    const closeMenus = () => {
        toggles.forEach((toggle) => {
            const menu = toggle.parentElement?.querySelector('[data-mobile-menu]');

            if (menu) {
                menu.classList.add('hidden');
            }
        });
    };

    toggles.forEach((toggle) => {
        const menu = toggle.parentElement?.querySelector('[data-mobile-menu]');

        if (!menu) {
            return;
        }

        toggle.setAttribute('aria-expanded', 'false');

        toggle.addEventListener('click', (event) => {
            event.stopPropagation();

            const isHidden = menu.classList.contains('hidden');
            closeMenus();
            menu.classList.toggle('hidden', !isHidden);
            toggle.setAttribute('aria-expanded', String(isHidden));
        });

        menu.addEventListener('click', (event) => event.stopPropagation());
    });

    document.addEventListener('click', closeMenus);
}

function initHomeSpotlight() {
    const spotlight = document.querySelector('[data-home-spotlight]');

    if (!spotlight) {
        return;
    }

    const items = [...spotlight.querySelectorAll('[data-home-spotlight-item]')];
    const tabs = [...spotlight.querySelectorAll('[data-home-spotlight-tab]')];
    const interval = Number(spotlight.dataset.homeSpotlightInterval ?? 5500);

    if (items.length === 0) {
        return;
    }

    let currentIndex = 0;
    let timer = null;

    const setActive = (index) => {
        currentIndex = index;

        items.forEach((item, itemIndex) => {
            const isActive = itemIndex === index;
            item.classList.toggle('opacity-100', isActive);
            item.classList.toggle('opacity-0', !isActive);
            item.classList.toggle('pointer-events-none', !isActive);
        });

        tabs.forEach((tab, tabIndex) => {
            const isActive = tabIndex === index;
            tab.classList.toggle('ring-2', isActive);
            tab.classList.toggle('ring-blue-500', isActive);
            tab.classList.toggle('bg-blue-50', isActive);
            tab.classList.toggle('border-blue-200', isActive);
            tab.classList.toggle('bg-white', !isActive);
        });
    };

    const start = () => {
        timer = window.setInterval(() => {
            setActive((currentIndex + 1) % items.length);
        }, interval);
    };

    const restart = () => {
        if (timer) {
            window.clearInterval(timer);
        }

        start();
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const index = Number(tab.dataset.homeSpotlightIndex ?? 0);
            setActive(index);
            restart();
        });
    });

    spotlight.addEventListener('mouseenter', () => {
        if (timer) {
            window.clearInterval(timer);
        }
    });

    spotlight.addEventListener('mouseleave', () => {
        restart();
    });

    setActive(0);
    start();
}

function initPlayerState() {
    const playerPage = document.querySelector('[data-player-page]');

    if (!playerPage) {
        return;
    }

    const config = window.learningHubPlayer ?? {};
    const storageKey = config.storageKey ?? 'lh-progress:default';
    let currentLessonId = config.currentLessonId ?? null;
    const video = playerPage.querySelector('[data-player-video]');
    const title = playerPage.querySelector('[data-current-lesson-title]');
    const content = playerPage.querySelector('[data-current-lesson-content]');
    const markCompleteButton = playerPage.querySelector('[data-mark-complete]');
    const lessonButtons = [...playerPage.querySelectorAll('[data-lesson-button]')];

    const completed = new Set(JSON.parse(localStorage.getItem(storageKey) ?? '[]'));

    lessonButtons.forEach((button) => {
        if (button.dataset.lessonCompleted === '1') {
            completed.add(Number(button.dataset.lessonId));
        }
    });

    const toEmbedUrl = (value) => {
        if (!value) {
            return '';
        }

        if (value.includes('youtu.be/')) {
            return `https://www.youtube-nocookie.com/embed/${value.split('youtu.be/')[1].split('?')[0]}`;
        }

        if (value.includes('youtube.com/watch')) {
            const match = value.match(/[?&]v=([^&]+)/);
            return match ? `https://www.youtube-nocookie.com/embed/${match[1]}` : value;
        }

        if (value.includes('drive.google.com/file/d/')) {
            const match = value.match(/\/file\/d\/([^/]+)/);
            return match ? `https://drive.google.com/file/d/${match[1]}/preview` : value;
        }

        if (value.includes('/embed/')) {
            return value;
        }

        return value;
    };

    const setLessonButtonState = () => {
        lessonButtons.forEach((button) => {
            const isActive = Number(button.dataset.lessonId) === Number(currentLessonId);
            const isCompleted = completed.has(Number(button.dataset.lessonId));
            const isLocked = button.dataset.lessonLocked === '1';
            const icon = button.querySelector('[data-lesson-icon]');

            button.classList.toggle('bg-blue-600/10', isActive);
            button.classList.toggle('text-white', isActive);

            if (icon) {
                if (isCompleted) {
                    icon.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><circle cx="12" cy="12" r="9"></circle><path d="m9 12 2 2 4-4"></path></svg>`;
                } else if (isLocked) {
                    icon.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-slate-500"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>`;
                } else {
                    icon.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-slate-650"><circle cx="12" cy="12" r="9"></circle></svg>`;
                }
            }
        });
    };

    const setLesson = (button) => {
        currentLessonId = Number(button.dataset.lessonId);

        if (title) {
            title.textContent = button.dataset.lessonTitle ?? '';
        }

        if (content) {
            content.textContent = button.dataset.lessonContent ?? '';
        }

        if (video) {
            video.src = toEmbedUrl(button.dataset.lessonVideo ?? '');
        }

        setLessonButtonState();
        localStorage.setItem(storageKey, JSON.stringify([...completed]));
    };

    lessonButtons.forEach((button) => {
        button.addEventListener('click', () => setLesson(button));
    });

    if (markCompleteButton) {
        markCompleteButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentLessonId === null) {
                return;
            }

            const url = markCompleteButton.dataset.completeUrl;
            if (!url) return;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.completed) {
                    markCompleteButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    markCompleteButton.classList.add('bg-green-600', 'hover:bg-green-700');
                    const textSpan = markCompleteButton.querySelector('[data-complete-text]');
                    if (textSpan) textSpan.textContent = 'Completed';
                    
                    completed.add(Number(currentLessonId));
                    localStorage.setItem(storageKey, JSON.stringify([...completed]));
                    setLessonButtonState();
                    
                    window.location.reload();
                }
            })
            .catch(err => console.error(err));
        });
    }

    setLessonButtonState();
}

function initCourseAdminEditor() {
    const editToggle = document.querySelector('[data-edit-toggle]');
    const editSave = document.querySelector('[data-edit-save]');
    const editCancel = document.querySelector('[data-edit-cancel]');
    const form = document.getElementById('course-edit-form');

    if (!editToggle || !form) {
        return;
    }

    const viewElements = form.querySelectorAll('.view-element');
    const editElements = form.querySelectorAll('.edit-element');
    const learnContainer = document.getElementById('learn-items-container');
    const addLearnItemBtn = document.getElementById('add-learn-item');

    let originalData = {};

    const captureState = () => {
        const formData = new FormData(form);
        originalData = {};
        for (let [key, value] of formData.entries()) {
            if (key.endsWith('[]')) {
                if (!originalData[key]) {
                    originalData[key] = [];
                }
                originalData[key].push(value);
            } else {
                originalData[key] = value;
            }
        }
        if (learnContainer) {
            originalData['learn_container_html'] = learnContainer.innerHTML;
        }
    };

    const restoreState = () => {
        for (let key in originalData) {
            if (key === 'learn_container_html') {
                if (learnContainer) {
                    learnContainer.innerHTML = originalData[key];
                    bindRemoveButtons();
                }
                continue;
            }

            if (key.endsWith('[]')) {
                const inputs = form.querySelectorAll(`[name="${key}"]`);
                inputs.forEach((input, index) => {
                    if (originalData[key][index] !== undefined) {
                        input.value = originalData[key][index];
                    }
                });
            } else {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = originalData[key];
                }
            }
        }
    };

    const toggleMode = (editMode) => {
        if (editMode) {
            captureState();
            viewElements.forEach(el => el.classList.add('hidden'));
            editElements.forEach(el => el.classList.remove('hidden'));
            editToggle.classList.add('hidden');
            editSave.classList.remove('hidden');
            editCancel.classList.remove('hidden');
        } else {
            viewElements.forEach(el => el.classList.remove('hidden'));
            editElements.forEach(el => el.classList.add('hidden'));
            editToggle.classList.remove('hidden');
            editSave.classList.add('hidden');
            editCancel.classList.add('hidden');
        }
    };

    editToggle.addEventListener('click', () => toggleMode(true));

    editCancel.addEventListener('click', () => {
        restoreState();
        toggleMode(false);
    });

    const bindRemoveButtons = () => {
        const removeBtns = learnContainer.querySelectorAll('.remove-learn-item');
        removeBtns.forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                const row = btn.closest('.learn-item-row');
                if (row) {
                    row.remove();
                }
            };
        });
    };

    if (addLearnItemBtn && learnContainer) {
        addLearnItemBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const newRow = document.createElement('div');
            newRow.className = 'flex items-center gap-2 learn-item-row';
            newRow.innerHTML = `
                <input type="text" name="what_you_will_learn[]" value="" class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:border-blue-500 focus:outline-none">
                <button type="button" class="remove-learn-item rounded-lg bg-red-100 p-2 text-red-600 hover:bg-red-200 transition">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                </button>
            `;
            learnContainer.appendChild(newRow);
            bindRemoveButtons();
        });
    }

    if (learnContainer) {
        bindRemoveButtons();
    }
}

function initStarRatingWidget() {
    const container = document.querySelector('[data-star-rating-container]');
    const form = document.getElementById('rating-form');
    const selectedInput = document.getElementById('selected-rating');
    const statusText = document.getElementById('rating-status-text');

    if (!container || !form || !selectedInput) {
        return;
    }

    const starBtns = [...container.querySelectorAll('.star-button')];
    const starSvgs = starBtns.map(btn => btn.querySelector('.star-svg'));

    let currentRating = parseInt(selectedInput.value) || 0;

    const renderStars = (rating) => {
        starSvgs.forEach((svg, index) => {
            const starNum = index + 1;
            if (starNum <= rating) {
                svg.setAttribute('fill', '#eab308');
                svg.setAttribute('stroke', '#eab308');
            } else {
                svg.setAttribute('fill', 'none');
                svg.setAttribute('stroke', 'currentColor');
            }
        });
    };

    starBtns.forEach(btn => {
        const val = parseInt(btn.dataset.ratingVal) || 0;

        btn.addEventListener('mouseover', () => {
            renderStars(val);
            if (statusText) {
                statusText.textContent = `Rate this: ${val} star${val > 1 ? 's' : ''}`;
            }
        });

        btn.addEventListener('mouseout', () => {
            renderStars(currentRating);
            if (statusText) {
                statusText.textContent = currentRating 
                    ? `You rated this: ${currentRating} star${currentRating > 1 ? 's' : ''}`
                    : 'Click a star to rate this course';
            }
        });

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            currentRating = val;
            selectedInput.value = val;
            form.submit();
        });
    });

    renderStars(currentRating);
}

function initLessonAdminEditor() {
    const editToggle = document.querySelector('[data-lesson-edit-toggle]');
    const editSave = document.querySelector('[data-lesson-edit-save]');
    const editCancel = document.querySelector('[data-lesson-edit-cancel]');
    const form = document.getElementById('lesson-edit-form');

    if (!editToggle || !form) {
        return;
    }

    const viewElements = form.querySelectorAll('.view-element');
    const editElements = form.querySelectorAll('.edit-element');
    const typeSelect = document.getElementById('lesson-type-select');
    const videoGroup = document.getElementById('edit-video-url-group');
    const quizGroup = document.getElementById('edit-quiz-details-group');
    const moduleGroup = document.getElementById('edit-module-details-group');
    const sectionsContainer = document.getElementById('module-sections-container');
    const addSectionBtn = document.getElementById('add-module-section');

    const rebuildModuleIndices = () => {
        if (!sectionsContainer) return;
        const sections = sectionsContainer.querySelectorAll('.section-row');
        sections.forEach((sec, secIdx) => {
            sec.setAttribute('data-section-index', secIdx);
            
            const titleInput = sec.querySelector('input[name*="[title]"]');
            if (titleInput) {
                titleInput.name = `module_content[${secIdx}][title]`;
            }
            
            const items = sec.querySelectorAll('.item-row');
            items.forEach((item, itemIdx) => {
                item.setAttribute('data-item-index', itemIdx);
                
                const subtitleInput = item.querySelector('input[name*="[subtitle]"]');
                if (subtitleInput) {
                    subtitleInput.name = `module_content[${secIdx}][items][${itemIdx}][subtitle]`;
                }
                
                const photoInput = item.querySelector('input[name*="[photo]"]');
                if (photoInput) {
                    photoInput.name = `module_content[${secIdx}][items][${itemIdx}][photo]`;
                }
                
                const contentTextarea = item.querySelector('textarea[name*="[content]"]');
                if (contentTextarea) {
                    contentTextarea.name = `module_content[${secIdx}][items][${itemIdx}][content]`;
                }
            });
        });
    };

    const toggleFieldsBasedOnType = () => {
        if (!typeSelect) return;
        const type = typeSelect.value;
        if (type === 'video') {
            if (videoGroup) videoGroup.classList.remove('hidden');
            if (quizGroup) quizGroup.classList.add('hidden');
            if (moduleGroup) moduleGroup.classList.add('hidden');
        } else if (type === 'quiz') {
            if (videoGroup) videoGroup.classList.add('hidden');
            if (quizGroup) quizGroup.classList.remove('hidden');
            if (moduleGroup) moduleGroup.classList.add('hidden');
        } else if (type === 'module') {
            if (videoGroup) videoGroup.classList.add('hidden');
            if (quizGroup) quizGroup.classList.add('hidden');
            if (moduleGroup) moduleGroup.classList.remove('hidden');
        } else {
            if (videoGroup) videoGroup.classList.add('hidden');
            if (quizGroup) quizGroup.classList.add('hidden');
            if (moduleGroup) moduleGroup.classList.add('hidden');
        }
    };

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleFieldsBasedOnType);
    }

    if (addSectionBtn && sectionsContainer) {
        addSectionBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const secIdx = sectionsContainer.querySelectorAll('.section-row').length;
            const secHtml = `
                <div class="section-row border border-slate-800 rounded-xl p-4 bg-slate-900/40 space-y-4" data-section-index="${secIdx}">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Section Title</label>
                            <input type="text" name="module_content[${secIdx}][title]" value="" required class="w-full rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-white text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <button type="button" class="remove-section-btn mt-5 rounded-lg bg-red-950/65 p-2 text-red-400 hover:bg-red-900/40 transition">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                        </button>
                    </div>

                    <div class="items-container pl-6 border-l border-slate-800 space-y-4"></div>

                    <button type="button" class="add-item-btn inline-flex items-center gap-1 rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-medium text-slate-300 hover:bg-slate-700 transition">
                        + Add Subsection Item
                    </button>
                </div>
            `;
            const wrapper = document.createElement('div');
            wrapper.innerHTML = secHtml.trim();
            sectionsContainer.appendChild(wrapper.firstChild);
            rebuildModuleIndices();
        });
    }

    if (sectionsContainer) {
        sectionsContainer.addEventListener('click', (e) => {
            const removeSecBtn = e.target.closest('.remove-section-btn');
            if (removeSecBtn) {
                e.preventDefault();
                const sectionRow = removeSecBtn.closest('.section-row');
                if (sectionRow) {
                    sectionRow.remove();
                    rebuildModuleIndices();
                }
                return;
            }

            const addItemBtn = e.target.closest('.add-item-btn');
            if (addItemBtn) {
                e.preventDefault();
                const sectionRow = addItemBtn.closest('.section-row');
                if (sectionRow) {
                    const itemsContainer = sectionRow.querySelector('.items-container');
                    if (itemsContainer) {
                        const secIdx = sectionRow.getAttribute('data-section-index');
                        const itemIdx = itemsContainer.querySelectorAll('.item-row').length;
                        const itemHtml = `
                            <div class="item-row border border-slate-850 rounded-lg p-3 bg-slate-950/20 space-y-3" data-item-index="${itemIdx}">
                                <div class="flex items-center justify-between">
                                    <h5 class="text-xs font-semibold text-slate-450">Subsection Item</h5>
                                    <button type="button" class="remove-item-btn rounded bg-red-950/40 px-2 py-1 text-[10px] text-red-400 hover:bg-red-950/60 transition">
                                        Remove Item
                                    </button>
                                </div>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-xs text-slate-400 mb-1">Subtitle</label>
                                        <input type="text" name="module_content[${secIdx}][items][${itemIdx}][subtitle]" value="" class="w-full rounded border border-slate-700 bg-slate-850 px-2 py-1 text-xs text-white focus:border-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-slate-400 mb-1">Image URL</label>
                                        <input type="text" name="module_content[${secIdx}][items][${itemIdx}][photo]" value="" class="w-full rounded border border-slate-700 bg-slate-850 px-2 py-1 text-xs text-white focus:border-blue-500 focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-slate-400 mb-1">Content</label>
                                    <textarea name="module_content[${secIdx}][items][${itemIdx}][content]" rows="3" class="w-full rounded border border-slate-700 bg-slate-850 px-2 py-1 text-xs text-white focus:border-blue-500 focus:outline-none"></textarea>
                                </div>
                            </div>
                        `;
                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = itemHtml.trim();
                        itemsContainer.appendChild(wrapper.firstChild);
                        rebuildModuleIndices();
                    }
                }
                return;
            }

            const removeItemBtn = e.target.closest('.remove-item-btn');
            if (removeItemBtn) {
                e.preventDefault();
                const itemRow = removeItemBtn.closest('.item-row');
                if (itemRow) {
                    itemRow.remove();
                    rebuildModuleIndices();
                }
                return;
            }
        });
    }

    let originalData = {};

    const captureState = () => {
        const formData = new FormData(form);
        originalData = {};
        for (let [key, value] of formData.entries()) {
            if (key.endsWith('[]')) {
                if (!originalData[key]) originalData[key] = [];
                originalData[key].push(value);
            } else {
                originalData[key] = value;
            }
        }
        if (sectionsContainer) {
            originalData['module_sections_html'] = sectionsContainer.innerHTML;
        }
    };

    const restoreState = () => {
        for (let key in originalData) {
            if (key === 'module_sections_html') {
                if (sectionsContainer) {
                    sectionsContainer.innerHTML = originalData[key];
                    rebuildModuleIndices();
                }
                continue;
            }
            if (key.endsWith('[]')) {
                const inputs = form.querySelectorAll(`[name="${key}"]`);
                inputs.forEach((input, index) => {
                    if (originalData[key][index] !== undefined) {
                        input.value = originalData[key][index];
                    }
                });
            } else {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = originalData[key];
                }
            }
        }
        toggleFieldsBasedOnType();
    };

    const toggleMode = (editMode) => {
        if (editMode) {
            captureState();
            toggleFieldsBasedOnType();
            viewElements.forEach(el => el.classList.add('hidden'));
            editElements.forEach(el => el.classList.remove('hidden'));
            editToggle.classList.add('hidden');
            editSave.classList.remove('hidden');
            editCancel.classList.remove('hidden');
        } else {
            viewElements.forEach(el => el.classList.remove('hidden'));
            editElements.forEach(el => el.classList.add('hidden'));
            editToggle.classList.remove('hidden');
            editSave.classList.add('hidden');
            editCancel.classList.add('hidden');
        }
    };

    editToggle.addEventListener('click', () => toggleMode(true));

    editCancel.addEventListener('click', () => {
        restoreState();
        toggleMode(false);
    });
}
