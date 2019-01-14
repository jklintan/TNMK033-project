const fadeElements = Array.from(document.querySelectorAll('.fade-up'))
const fadeTriggers = []
window.addEventListener('scroll', (e) => {
  windowY = window.pageYOffset
  fadeTriggers.forEach((obj, index) => {
    if (windowY + window.innerHeight > obj.trigger) {
      obj.element.style.transform = 'translateY(0)'
      obj.element.style.opacity = 1
      fadeTriggers.splice(index, 1);
    }
  })
})
for (let element of fadeElements) {
  const entry = {
    trigger: element.offsetTop - element.scrollTop + element.clientTop,
    element: element
  }
  element.style.transition = '800ms'
  element.style.transform = 'translateY(200px)'
  element.style.opacity = 0
  fadeTriggers.push(entry)
}