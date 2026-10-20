document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-registration-wizard]');

    if (!form) return;

    const levels = ['provinces', 'regencies', 'districts', 'villages'];
    const regionSelects = levels.map((level) => form.querySelector(`[data-region="${level}"]`));
    const steps = Array.from(form.querySelectorAll('[data-registration-step]'));
    const indicators = Array.from(form.querySelectorAll('[data-step-indicator]'));
    const stepCount = form.querySelector('[data-step-count]');
    const progress = form.querySelector('[data-step-progress]');
    const regionError = form.querySelector('[data-region-error]');
    const errorStepIndex = steps.findIndex((step) => step.querySelector('.register-field small:not(.register-field-optional)'));
    let currentStep = errorStepIndex >= 0 ? errorStepIndex : 0;

    const showStep = (index, focus = false) => {
        currentStep = index;

        steps.forEach((step, stepIndex) => {
            const isActive = stepIndex === index;
            step.hidden = !isActive;
            step.disabled = !isActive;
            if (isActive) step.removeAttribute('aria-hidden');
            else step.setAttribute('aria-hidden', 'true');
        });

        indicators.forEach((indicator, stepIndex) => {
            indicator.classList.toggle('is-active', stepIndex === index);
            indicator.classList.toggle('is-complete', stepIndex < index);
            if (stepIndex === index) indicator.setAttribute('aria-current', 'step');
            else indicator.removeAttribute('aria-current');
        });

        if (stepCount) stepCount.textContent = `Tahap ${index + 1} dari ${steps.length}`;
        if (progress) progress.style.width = `${(index / (steps.length - 1)) * 100}%`;

        if (focus) {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
            requestAnimationFrame(() => steps[index].querySelector('input:not(:disabled), select:not(:disabled), textarea:not(:disabled)')?.focus({ preventScroll: true }));
        }
    };

    const validateStep = (step) => {
        const invalidField = Array.from(step.querySelectorAll('input, select, textarea'))
            .find((field) => !field.disabled && !field.checkValidity());

        if (invalidField) {
            invalidField.reportValidity();
            return false;
        }

        if (step.dataset.stepTitle === 'Lokasi instansi' && regionSelects.some((select) => !select?.value)) {
            if (regionError) regionError.hidden = false;
            regionSelects.find((select) => !select?.value && !select?.disabled)?.focus();
            return false;
        }

        if (regionError) regionError.hidden = true;
        return true;
    };

    form.querySelectorAll('[data-step-next]').forEach((button) => {
        button.addEventListener('click', () => {
            if (validateStep(steps[currentStep]) && currentStep < steps.length - 1) {
                showStep(currentStep + 1, true);
            }
        });
    });

    form.querySelectorAll('[data-step-back]').forEach((button) => {
        button.addEventListener('click', () => showStep(Math.max(0, currentStep - 1), true));
    });

    const password = form.querySelector('[name="password"]');
    const confirmation = form.querySelector('[name="password_confirmation"]');
    const validatePasswordMatch = () => {
        if (confirmation) {
            confirmation.setCustomValidity(
                confirmation.value && confirmation.value !== password?.value ? 'Kata sandi belum sama.' : '',
            );
        }
    };

    password?.addEventListener('input', validatePasswordMatch);
    confirmation?.addEventListener('input', validatePasswordMatch);

    const resetSelect = (select, prompt) => {
        if (!select) return;
        select.innerHTML = `<option value="">${prompt}</option>`;
        select.disabled = true;
    };

    const loadRegions = async (index, parentId = null) => {
        const select = regionSelects[index];
        if (!select) return;

        select.innerHTML = '<option value="">Memuat data wilayah...</option>';
        select.disabled = true;

        try {
            const path = `/api/wilayah/${levels[index]}${parentId ? `/${encodeURIComponent(parentId)}` : ''}`;
            const response = await fetch(path, { headers: { Accept: 'application/json' } });
            if (!response.ok) throw new Error('Data wilayah tidak tersedia');

            const rows = await response.json();
            select.innerHTML = '<option value="">Pilih wilayah</option>';
            rows.forEach((row) => {
                const option = document.createElement('option');
                option.value = row.id;
                option.textContent = row.name;
                select.append(option);
            });
            select.disabled = false;
            if (select.dataset.oldValue && rows.some((row) => String(row.id) === select.dataset.oldValue)) {
                select.value = select.dataset.oldValue;
                select.dispatchEvent(new Event('change', { bubbles: true }));
            }
        } catch {
            select.innerHTML = '<option value="">Data wilayah gagal dimuat</option>';
            if (regionError) regionError.hidden = false;
        }
    };

    regionSelects.forEach((select, index) => {
        select?.addEventListener('change', () => {
            const prompts = ['Pilih provinsi dahulu', 'Pilih kabupaten / kota dahulu', 'Pilih kecamatan dahulu'];
            for (let next = index + 1; next < regionSelects.length; next += 1) {
                resetSelect(regionSelects[next], prompts[next - 1] || 'Pilih wilayah dahulu');
            }

            if (index < levels.length - 1 && select.value) loadRegions(index + 1, select.value);
            if (regionError) regionError.hidden = true;
        });
    });

    form.addEventListener('submit', (event) => {
        if (!validateStep(steps[currentStep])) {
            event.preventDefault();
            return;
        }

        steps.forEach((step) => {
            step.disabled = false;
            step.removeAttribute('aria-hidden');
        });

        const address = form.querySelector('[name="address"]');
        const regionNames = regionSelects
            .map((select) => select?.selectedOptions[0]?.textContent)
            .filter((name) => name && name !== 'Pilih wilayah');

        if (address && regionNames.length) {
            address.dataset.baseAddress ??= address.value;
            address.value = [address.dataset.baseAddress, ...regionNames].filter(Boolean).join(', ');
        }
    });

    showStep(currentStep);
    loadRegions(0);
});
