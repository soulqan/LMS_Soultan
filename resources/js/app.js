document.addEventListener('DOMContentLoaded', () => {
    initCatalogFilters();
    initCatalogMobileFilters();
    initProfileDropdown();
    initMobileMenus();
    initHomeSpotlight();
    initPlayerState();
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
    const tabButtons = [...playerPage.querySelectorAll('[data-tab-target]')];
    const tabPanels = [...playerPage.querySelectorAll('[data-tab-panel]')];

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

    const setTab = (target) => {
        tabButtons.forEach((button) => {
            const isActive = button.dataset.tabTarget === target;
            button.classList.toggle('bg-white', isActive);
            button.classList.toggle('text-slate-900', isActive);
            button.classList.toggle('text-slate-300', !isActive);
            button.classList.toggle('bg-slate-800', !isActive);
        });

        tabPanels.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
        });
    };

    const setLessonButtonState = () => {
        lessonButtons.forEach((button) => {
            const isActive = Number(button.dataset.lessonId) === Number(currentLessonId);
            const isCompleted = completed.has(Number(button.dataset.lessonId));
            const icon = button.querySelector('[data-lesson-icon]');

            button.classList.toggle('bg-blue-600/10', isActive);
            button.classList.toggle('text-white', isActive);

            if (icon) {
                icon.innerHTML = isCompleted
                    ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><circle cx="12" cy="12" r="9"></circle><path d="m9 12 2 2 4-4"></path></svg>`
                    : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-slate-500"><circle cx="12" cy="12" r="9"></circle></svg>`;
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

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => setTab(button.dataset.tabTarget ?? 'description'));
    });

    if (markCompleteButton) {
        markCompleteButton.addEventListener('click', () => {
            if (currentLessonId === null) {
                return;
            }

            completed.add(Number(currentLessonId));
            setLessonButtonState();
            localStorage.setItem(storageKey, JSON.stringify([...completed]));
        });
    }

    setTab('description');
    setLessonButtonState();
}
