/** @param {string} type */
/** @param {string} jsondata */
/** @param {string} targetSelector */
const createGraph = (type, jsondata, targetSelector) => {
  const graph = JSON.parse(jsondata)
  if (graph.data[0].number) {
    graph.data.forEach(entry => {
          entry.number = parseInt(entry.number)
      })
  }
  let graphElement
  if (type === 'numberSlider') {
    graphElement = createNumberSlider(graph)
  }
  if (type === 'histogram') {
    graphElement = createHistogram(graph)
  }
  if (type === 'pieChart') {
    graphElement = createPieChart(graph)
  }
  if (type === 'timeChart') {
    graphElement = createTimeChart(graph)
  }

  if (graphElement) {
    const target = document.querySelector(targetSelector)
    if (target) {
      graphElement.classList.add('fade-up')
      target.appendChild(graphElement)
    } else {
      console.log('No target html object found')
    }
  } else {
    console.log('No such graph existing')
  }
}

const generateTestData = (amount) => {
  let testData = []
  for (let i = 0; i < amount; i++) {
    const element = { number: Math.random() * 10, text: 'datanamn' }
    testData.push(element)
  }
  const result = { data: testData }
  return result
}

const init = () => {
  document.querySelector('body').appendChild(createPieChart(generateTestData(4)))
  document.querySelector('body').appendChild(createTimeChart(generateTestData(2)))
}

/** @param {Number} goTo */
/** @param {HTMLDivElement} element */
const numberSliderClick = (element, goTo) => {
  const slider = element.querySelector('div')
  const children = [].slice.call(element.children)
  if (children.length - 1 >= parseInt(slider.dataset.slider) + goTo && 0 <= parseInt(slider.dataset.slider) + goTo ) {
    const pos = parseInt(slider.dataset.slider) + goTo
    slider.style.transform = 'translateX(-' + pos * (100 / children.length) + '%)'
    slider.dataset.slider = pos
  }
}

/**
 * @param {dataobj[]} sliderData
 * @typedef {Object}  dataobj             - The data object
 * @property {Number}  dataobj.title       - The title
 * @property {String}  dataobj.text         - The text describing the data
 * @property {Number}  dataobj.number      - The number to diplay
 */
const createNumberSlider = (sliderData) => {
  const parent = document.createElement('div')
  const header = document.createElement('h2')
  const slider = document.createElement('div')
  const arrowRight = document.createElement('a')
  const arrowLeft = document.createElement('a')

  parent.classList.add('grapher', 'numberSlider')
  header.textContent = sliderData.title
  slider.style.width = sliderData.data.length * 100 + '%'
  slider.classList.add('slider')
  slider.dataset.slider = 0 // The data to show
  sliderData.data.forEach(object => {
    const entry = document.createElement('div')
    const p = document.createElement('p')
    p.textContent = object.number + ' ' + object.text
    entry.appendChild(p)
    slider.appendChild(entry)
  })

  arrowRight.classList.add('right')
  arrowRight.addEventListener('click', () => numberSliderClick(parent, 1))
  arrowRight.textContent = '▶'
  arrowLeft.classList.add('left')
  arrowLeft.addEventListener('click', () => numberSliderClick(parent, -1))
  arrowLeft.textContent = '◀'

  parent.appendChild(header)
  parent.appendChild(arrowLeft)
  parent.appendChild(arrowRight)
  parent.appendChild(slider)
  return parent
}

const findTallestData = (histogramData) => {
  if (histogramData.data[0].number) {
    let tallest = histogramData.data[0].number
    histogramData.data.forEach(data => {
      tallest = data.number > tallest ? data.number : tallest
    })
    return tallest
  }
  console.log('Incorrect data type')
  return false
}

/**
 * @param {dataobj[]} barData
 * @typedef {Object}  dataobj              - The data object
 * @property {Number}  dataobj.text    - The text describing the part
 * @property {Number}  dataobj.number    - The number to diplay
 */
const createBar = (barData, dataType, tallest) => {
  const bar = document.createElement('div')
  const info = document.createElement('div')
  const number = document.createElement('p')
  const text = document.createElement('p')
  const height = (barData.number / tallest) * 100

  let numberString
  if (dataType) {
    numberString = barData.number + ' ' + dataType
  } else {
    numberString = barData.number
  }
  if (height < 100 / 3) {
    bar.classList.add('top')
  }
  bar.style.height = height + '%'
  bar.classList.add('bar')
  info.classList.add('barInfo')
  text.textContent = barData.text
  number.textContent = numberString
  info.appendChild(text)
  info.appendChild(number)
  bar.appendChild(info)
  return bar
}

/**
 * @param {dataobj[]} histogramData
 * @typedef {Object}  dataobj              - The data object
 * @property {Number}  dataobj.text    - The text describing the part
 * @property {Number}  dataobj.number    - The number to diplay
 */
const createHistogram = (histogramData) => {
  const tallest = findTallestData(histogramData)
  const parent = document.createElement('div')
  const scrollbox = document.createElement('div')
  const title = document.createElement('h2')
  title.textContent = histogramData.title
  parent.classList.add('grapher', 'histogram')
  parent.appendChild(title)

  let delay = 0
  const step = 1000 / histogramData.length
  const length = histogramData.data
  histogramData.data.forEach(entry => {
    const bar = createBar(entry, histogramData.dataType, tallest)
    bar.style.animationDelay = delay + 'ms'
    delay += step
    scrollbox.appendChild(bar)
  })
  const spacer = document.createElement('div')
  spacer.style.padding = '10px'
  scrollbox.appendChild(spacer)
  scrollbox.classList.add('slider')
  parent.appendChild(scrollbox)
  return parent
}

/**
 * @param {dataobj[]} pieChartData
 * @typedef {Object}  dataobj              - The data object
 * @property {Number}  dataobj.text    - The text describing the part
 * @property {Number}  dataobj.number    - The number to diplay
 */
const createPieChart = (pieChartData) => {
  const createPie = (data, color) => {
    const procent = data.number / total
    const slot = document.createElement('div')
    const intersect = document.createElement('div')
    slot.classList.add('slot')
    slot.style.transform = 'rotate(' + offset + 'deg)'
    offset += procent * 360
    intersect.classList.add('intersect')
    intersect.style.transform = 'rotate(' + (procent * 360) + 'deg)'
    intersect.style.backgroundColor = color
    if (procent > .5) {
      slot.style.overflow = 'visible'
      slot.style.backgroundColor = color
      slot.style.display = 'flex'
    }
    slot.appendChild(intersect)
    return slot
  }

  const createPieLabel = (data, color) => {
    const label = document.createElement('div')
    const dot = document.createElement('div')
    const p = document.createElement('p')
    const procentString = (Math.round(data.number / total * 1000) / 10) + '%'
    dot.style.backgroundColor = color
    p.textContent = procentString + ' ' + data.text
    label.appendChild(dot)
    label.appendChild(p)
    return label
  }

  const parent = document.createElement('div')
  const title = document.createElement('h2')
  const chart = document.createElement('div')
  const list = document.createElement('div')
  parent.classList.add('pieChart', 'grapher')
  title.textContent = pieChartData.title
  chart.classList.add('chart')
  list.classList.add('list')
  let total = 0
  pieChartData.data.forEach(data => {
    total += data.number
  })
  let offset = 0
  const colors = generateColors(pieChartData.data.length)
  let i = 0
  pieChartData.data.forEach(data => {
    chart.appendChild(createPie(data, colors[i]))
    list.appendChild(createPieLabel(data, colors[i]))
    i += 1
  })
  parent.appendChild(title)
  parent.appendChild(chart)
  parent.appendChild(list)
  return parent
}

const generateColors = (length) => {
  let colors = []
  const base = 255 / length

  for (let i = 0; i < length; i++) {
    const res = Math.round(base * (i + 1))
    colors[i] = 'rgb(' + res + ', ' + res + ', ' + res + ')'
  }

  return colors
}

/**
 * @param {dataobj[]} timeData
 * @typedef {Object}  dataobj              - The data object
 * @property {Number}  dataobj.text    - The text describing the part
 * @property {Number}  dataobj.number    - The number to diplay
 */
const createTimeChart = (timeData) => {
  const getYPos = (number) => {
    const res = number * (height - 40) / tallest + 20
    return height - res
  }
  const hover = (data) => {
    if (data) {
      dataTitle.textContent = data.text + ', ' + Math.round(data.number * 100) / 100 + ' ' + timeData.dataType
    } else {
      dataTitle.textContent = ''
    }
  }
  const createLine = (x, y, lastX, lastY, graph, lineData) => {
    const svgNS = 'http://www.w3.org/2000/svg'

    const rect = document.createElementNS(svgNS, 'rect')
    rect.classList.add('rect')
    rect.addEventListener('mouseover', (e) => {
      hover(lineData)
    })
    rect.addEventListener('mouseleave', (e) => {
      hover('')
    })
    rect.setAttributeNS(null, 'x', x - step / 2)
    rect.setAttributeNS(null, 'width', step)
    rect.setAttributeNS(null, 'height', height)
    rect.setAttributeNS(null, 'fill', 'rgba(255, 255, 255, 0)')
    graph.appendChild(rect)

    const circle = document.createElementNS(svgNS, 'circle')
    circle.classList.add('dot')
    circle.setAttributeNS(null, 'cx', x)
    circle.setAttributeNS(null, 'cy', y)
    circle.setAttributeNS(null, 'r', 4)
    circle.setAttributeNS(null, 'fill', '#fff')
    circle.setAttributeNS(null, 'stroke', 'none')
    graph.appendChild(circle)

    const line = document.createElementNS(svgNS, 'line')
    line.setAttributeNS(null, 'x1', x)
    line.setAttributeNS(null, 'y1', y)
    line.setAttributeNS(null, 'x2', lastX)
    line.setAttributeNS(null, 'y2', lastY)
    line.setAttributeNS(null, 'stroke', '#fff')
    graph.appendChild(line)
  }

  const tallest = findTallestData(timeData)
  const step = 40
  const height = 400
  const parent = document.createElement('div')
  const slider = document.createElement('div')
  const graph = document.createElementNS('http://www.w3.org/2000/svg', 'svg')
  const textContainer = document.createElement('div')
  const title = document.createElement('h2')
  const dataTitle = document.createElement('h3')
  parent.classList.add('grapher', 'timeChart')
  slider.classList.add('slider')
  graph.classList.add('graph')
  graph.style.height = height
  graph.style.minWidth = timeData.data.length * step
  graph.style.width = timeData.data.length * step
  textContainer.classList.add('textContainer')
  title.textContent = timeData.title

  let currentX = step / 2
  let lastX = step / 2
  let lastY = getYPos(timeData.data[0].number)
  timeData.data.forEach(lineData => {
    createLine(currentX, getYPos(lineData.number), lastX, lastY, graph, lineData)
    lastX = currentX
    lastY = getYPos(lineData.number)
    currentX += step
  })
  textContainer.appendChild(title)
  textContainer.appendChild(dataTitle)
  parent.appendChild(textContainer)
  slider.appendChild(graph)
  parent.appendChild(slider)
  return parent
}
