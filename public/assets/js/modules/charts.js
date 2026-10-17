/* =========================================================================
   Vipasa Yoga — Executive SVG Chart Engine (High Resolution & Y-Axis Labels)
   Supports Date, Month, and Year granularities with explicit Y-axis scales,
   smooth Bezier curves, linear SVG gradients, dashed grid lines, and tooltips.
   ========================================================================= */

const WIDTH = 500;
const HEIGHT = 220;
const PADDING = { top: 24, right: 24, bottom: 38, left: 55 };

function svgEl(tag, attrs = {}) {
  const el = document.createElementNS('http://www.w3.org/2000/svg', tag);
  Object.entries(attrs).forEach(([key, value]) => el.setAttribute(key, value));
  return el;
}

function niceMax(max) {
  if (max <= 0) return 10;
  const magnitude = 10 ** Math.floor(Math.log10(max));
  const normalized = max / magnitude;
  let niceNorm = 10;
  if (normalized <= 1) niceNorm = 1;
  else if (normalized <= 2) niceNorm = 2;
  else if (normalized <= 5) niceNorm = 5;
  else niceNorm = 10;
  return Math.max(10, Math.ceil(niceNorm * magnitude));
}

function formatYLabel(value, isCurrency = false) {
  if (isCurrency) {
    if (value >= 100000) return '₹' + (value / 1000).toFixed(0) + 'k';
    if (value >= 1000) return '₹' + (value / 1000).toFixed(1).replace('.0', '') + 'k';
    return '₹' + Math.round(value);
  }
  if (value >= 1000) return (value / 1000).toFixed(1).replace('.0', '') + 'k';
  return Math.round(value).toString();
}

function getBezierPath(points) {
  if (points.length < 2) return '';
  let d = `M ${points[0][0].toFixed(1)} ${points[0][1].toFixed(1)}`;
  for (let i = 0; i < points.length - 1; i++) {
    const p0 = points[i === 0 ? i : i - 1];
    const p1 = points[i];
    const p2 = points[i + 1];
    const p3 = points[i + 2 < points.length ? i + 2 : i + 1];

    const cp1x = p1[0] + (p2[0] - p0[0]) / 6;
    const cp1y = p1[1] + (p2[1] - p0[1]) / 6;
    const cp2x = p2[0] - (p3[0] - p1[0]) / 6;
    const cp2y = p2[1] - (p3[1] - p1[1]) / 6;

    d += ` C ${cp1x.toFixed(1)} ${cp1y.toFixed(1)}, ${cp2x.toFixed(1)} ${cp2y.toFixed(1)}, ${p2[0].toFixed(1)} ${p2[1].toFixed(1)}`;
  }
  return d;
}

export function renderLineChart(container, { labels, values }, options = {}) {
  if (!container || !values || values.length === 0) return;

  const isCurrency = options.isCurrency || false;
  const color = options.color || 'var(--color-primary, #0F3349)';
  const max = niceMax(Math.max(...values, 0));
  const innerW = WIDTH - PADDING.left - PADDING.right;
  const innerH = HEIGHT - PADDING.top - PADDING.bottom;
  const stepX = values.length > 1 ? innerW / (values.length - 1) : 0;

  const points = values.map((v, i) => {
    const x = PADDING.left + stepX * i;
    const y = PADDING.top + innerH - (v / max) * innerH;
    return [x, y];
  });

  const linePath = getBezierPath(points);
  const areaPath = `${linePath} L ${points[points.length - 1][0].toFixed(1)} ${PADDING.top + innerH} L ${points[0][0].toFixed(1)} ${PADDING.top + innerH} Z`;

  const svg = svgEl('svg', { viewBox: `0 0 ${WIDTH} ${HEIGHT}`, class: 'chart-svg', role: 'img', style: 'width:100%;height:100%;min-height:220px;' });
  const gradId = 'grad-line-' + Math.random().toString(36).substr(2, 6);

  const defs = svgEl('defs');
  const grad = svgEl('linearGradient', { id: gradId, x1: '0', y1: '0', x2: '0', y2: '1' });
  grad.appendChild(svgEl('stop', { offset: '0%', 'stop-color': color, 'stop-opacity': '0.38' }));
  grad.appendChild(svgEl('stop', { offset: '100%', 'stop-color': color, 'stop-opacity': '0.03' }));
  defs.appendChild(grad);
  svg.appendChild(defs);

  // Y-Axis Horizontal Grid Lines & Y-Axis Scale Labels
  const gridTicks = 4;
  for (let g = 0; g <= gridTicks; g++) {
    const gridY = PADDING.top + (innerH / gridTicks) * g;
    const tickVal = max - (max / gridTicks) * g;

    // Grid Line
    svg.appendChild(svgEl('line', {
      x1: PADDING.left,
      y1: gridY.toFixed(1),
      x2: WIDTH - PADDING.right,
      y2: gridY.toFixed(1),
      stroke: 'rgba(15, 51, 73, 0.08)',
      'stroke-dasharray': '4 4'
    }));

    // Y-Axis Label
    const yText = svgEl('text', {
      x: (PADDING.left - 8).toFixed(1),
      y: (gridY + 3).toFixed(1),
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'end',
      fill: 'var(--text-muted, #64748B)',
    });
    yText.textContent = formatYLabel(tickVal, isCurrency);
    svg.appendChild(yText);
  }

  // Filled Area & Bezier Line
  svg.appendChild(svgEl('path', { d: areaPath, fill: `url(#${gradId})`, stroke: 'none' }));
  svg.appendChild(svgEl('path', { d: linePath, fill: 'none', stroke: color, 'stroke-width': '3', 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }));

  // Data Dots
  const dotStep = Math.max(1, Math.floor(values.length / 12));
  points.forEach(([x, y], i) => {
    if (i % dotStep === 0 || i === points.length - 1) {
      const dot = svgEl('circle', {
        cx: x.toFixed(1),
        cy: y.toFixed(1),
        r: '4',
        fill: '#ffffff',
        stroke: color,
        'stroke-width': '2.5',
        style: 'cursor:pointer;transition:transform 0.2s'
      });
      const formattedVal = isCurrency ? '₹' + values[i].toLocaleString() : values[i].toLocaleString();
      dot.appendChild(svgEl('title', {})).textContent = `${labels[i]}: ${formattedVal}`;
      svg.appendChild(dot);
    }
  });

  // X Axis Date / Month / Year Labels
  const labelStep = Math.max(1, Math.ceil(labels.length / 7));
  labels.forEach((label, i) => {
    if (i % labelStep !== 0 && i !== labels.length - 1) return;
    const text = svgEl('text', {
      x: points[i][0].toFixed(1),
      y: HEIGHT - 10,
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'middle',
      fill: 'var(--text-muted, #64748B)',
    });
    text.textContent = label;
    svg.appendChild(text);
  });

  container.innerHTML = '';
  container.appendChild(svg);
}

export function renderBarChart(container, { labels, values }, options = {}) {
  if (!container || !values || values.length === 0) return;

  const isCurrency = options.isCurrency || false;
  const color = options.color || 'var(--color-accent, #E07A5F)';
  const max = niceMax(Math.max(...values, 0));
  const innerW = WIDTH - PADDING.left - PADDING.right;
  const innerH = HEIGHT - PADDING.top - PADDING.bottom;
  const gap = values.length > 10 ? 6 : 14;
  const rawBarW = (innerW - gap * (values.length - 1)) / values.length;
  const barW = Math.min(rawBarW, 40);
  const groupW = barW * values.length + gap * (values.length - 1);
  const startX = PADDING.left + Math.max(0, (innerW - groupW) / 2);

  const svg = svgEl('svg', { viewBox: `0 0 ${WIDTH} ${HEIGHT}`, class: 'chart-svg', role: 'img', style: 'width:100%;height:100%;min-height:220px;' });
  const gradId = 'grad-bar-' + Math.random().toString(36).substr(2, 6);

  const defs = svgEl('defs');
  const grad = svgEl('linearGradient', { id: gradId, x1: '0', y1: '0', x2: '0', y2: '1' });
  grad.appendChild(svgEl('stop', { offset: '0%', 'stop-color': color, 'stop-opacity': '1' }));
  grad.appendChild(svgEl('stop', { offset: '100%', 'stop-color': color, 'stop-opacity': '0.75' }));
  defs.appendChild(grad);
  svg.appendChild(defs);

  // Y-Axis Horizontal Grid Lines & Y-Axis Scale Labels
  const gridTicks = 4;
  for (let g = 0; g <= gridTicks; g++) {
    const gridY = PADDING.top + (innerH / gridTicks) * g;
    const tickVal = max - (max / gridTicks) * g;

    svg.appendChild(svgEl('line', {
      x1: PADDING.left,
      y1: gridY.toFixed(1),
      x2: WIDTH - PADDING.right,
      y2: gridY.toFixed(1),
      stroke: 'rgba(15, 51, 73, 0.08)',
      'stroke-dasharray': '4 4'
    }));

    const yText = svgEl('text', {
      x: (PADDING.left - 8).toFixed(1),
      y: (gridY + 3).toFixed(1),
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'end',
      fill: 'var(--text-muted, #64748B)',
    });
    yText.textContent = formatYLabel(tickVal, isCurrency);
    svg.appendChild(yText);
  }

  // Bars & X-Axis Labels
  const labelStep = Math.max(1, Math.ceil(labels.length / 7));

  values.forEach((v, i) => {
    const barH = max > 0 ? (v / max) * innerH : 0;
    const x = startX + i * (barW + gap);
    const y = PADDING.top + innerH - barH;

    const rect = svgEl('rect', {
      x: x.toFixed(1),
      y: y.toFixed(1),
      width: Math.max(barW, 2).toFixed(1),
      height: Math.max(barH, 1).toFixed(1),
      rx: '5',
      ry: '5',
      fill: `url(#${gradId})`,
      style: 'transition:opacity 0.2s;cursor:pointer'
    });
    const formattedVal = isCurrency ? '₹' + v.toLocaleString() : v.toLocaleString();
    rect.appendChild(svgEl('title', {})).textContent = `${labels[i]}: ${formattedVal}`;
    svg.appendChild(rect);

    if (i % labelStep !== 0 && i !== labels.length - 1) return;
    const text = svgEl('text', {
      x: (x + barW / 2).toFixed(1),
      y: HEIGHT - 10,
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'middle',
      fill: 'var(--text-muted, #64748B)',
    });
    const labelStr = labels[i] || '';
    text.textContent = labelStr.length > 12 ? labelStr.slice(0, 11) + '…' : labelStr;
    svg.appendChild(text);
  });

  container.innerHTML = '';
  container.appendChild(svg);
}
