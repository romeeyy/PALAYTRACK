<script>
    (() => {
        const root = document.documentElement;
        const preference = root.dataset.displayPreference || 'light';
        const media = window.matchMedia('(prefers-color-scheme: dark)');
        const apply = () => {
            root.dataset.theme = preference === 'system'
                ? (media.matches ? 'dark' : 'light')
                : preference;
        };
        apply();
        media.addEventListener?.('change', apply);

        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Chart || root.dataset.theme !== 'dark') return;

            Chart.defaults.color = '#b8c5d6';
            Object.values(Chart.instances || {}).forEach((chart) => {
                if (chart.options?.plugins?.legend?.labels) {
                    chart.options.plugins.legend.labels.color = '#cbd5e1';
                }
                Object.values(chart.options?.scales || {}).forEach((scale) => {
                    scale.ticks = { ...(scale.ticks || {}), color: '#aebdd0' };
                    scale.title = { ...(scale.title || {}), color: '#cbd5e1' };
                    scale.grid = { ...(scale.grid || {}), color: 'rgba(148,163,184,.22)' };
                });
                chart.update('none');
            });
        });
    })();
</script>
