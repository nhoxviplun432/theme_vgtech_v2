import '../css/app.css'

/*
|--------------------------------------------------------------------------
| Init theme immediately (tránh flash trắng)
|--------------------------------------------------------------------------
*/

(function () {
  try {
    const savedTheme = localStorage.getItem('theme')

    if (
      savedTheme === 'dark' ||
      (!savedTheme &&
        window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
      document.documentElement.classList.add('dark')
    }
  } catch (e) {
    // ignore nếu localStorage bị chặn
  }
})()


/*
|--------------------------------------------------------------------------
| Toggle button
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('theme-toggle')
  if (!toggle) return

  toggle.addEventListener('click', () => {
    const html = document.documentElement
    const isDark = html.classList.toggle('dark')

    try {
      localStorage.setItem('theme', isDark ? 'dark' : 'light')
    } catch (e) {}
  })
})