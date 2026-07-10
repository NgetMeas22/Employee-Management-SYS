(function () {
    function escapeHtml(value) {
        return value
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getSubmitButton(form, event) {
        if (event.submitter && event.submitter.matches('button, input')) {
            return event.submitter;
        }

        return form.querySelector('button[type="submit"], input[type="submit"], button:not([type])');
    }

    function preserveSubmitterValue(form, button) {
        if (!button.name) {
            return;
        }

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = button.name;
        hidden.value = button.value || '1';
        form.appendChild(hidden);
    }

    document.addEventListener('submit', function (event) {
        if (event.defaultPrevented) {
            return;
        }

        const form = event.target;
        const button = getSubmitButton(form, event);

        if (!button || button.dataset.loading === 'true') {
            return;
        }

        button.dataset.loading = 'true';
        preserveSubmitterValue(form, button);
        button.disabled = true;

        if (button.tagName === 'INPUT') {
            const label = button.value.trim() || 'Submitting';
            button.dataset.originalValue = button.value;
            button.value = button.dataset.loadingText || `${label}.....`;
            return;
        }

        const label = button.dataset.loadingText || `${button.textContent.trim() || 'Submitting'}.....`;
        button.dataset.originalHtml = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>' + escapeHtml(label);
    });
})();
