import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/D:\wamp64\www\argus\lms\mylearning.live\app\Filament\Clusters\CourseMaster\**/*.php',
        './resources/views/filament/d:\wamp64\www\argus\lms\mylearning.live\app\-filament\-clusters\-course-master\**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            spacing: {
                '2': '0.5rem',  // Adding or customizing the gap-2 class
            },
        },
    },
}
