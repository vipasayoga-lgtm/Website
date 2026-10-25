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

function cleanAxisLabel(label) {
  if (typeof label === 'string' && label.includes(' ')) {
    const parts = label.split(' ');
    if (parts.length === 2 && !isNaN(parts[1])) {
      return parts[0];
    }
  }
  return label;
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

// =========================================================================
// Executive Analytics Engine — Direct visual realization of Image 2
// =========================================================================

function polarToCartesian(cx, cy, r, angleInDegrees) {
  const angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
  return {
    x: cx + (r * Math.cos(angleInRadians)),
    y: cy + (r * Math.sin(angleInRadians))
  };
}

export function renderAreaChart(container, { labels, values, values2 }, options = {}) {
  if (!container || !values || values.length === 0) return;

  const isCurrency = options.isCurrency || false;
  const color = options.color || '#38BDF8';
  const width = options.width || 600;
  const height = options.height || 220;
  const padding = { top: 20, right: 20, bottom: 35, left: 55 };

  const maxVal = Math.max(...values, ...(values2 || []), 0);
  const max = niceMax(maxVal);
  const innerW = width - padding.left - padding.right;
  const innerH = height - padding.top - padding.bottom;
  const stepX = values.length > 1 ? innerW / (values.length - 1) : 0;

  const points = values.map((v, i) => {
    const x = padding.left + stepX * i;
    const y = padding.top + innerH - (max > 0 ? (v / max) * innerH : 0);
    return [x, y];
  });

  const linePath = getBezierPath(points);
  let areaPath = '';
  if (points.length === 1) {
    const x = points[0][0];
    const y = points[0][1];
    areaPath = `M ${(x - 15).toFixed(1)} ${(padding.top + innerH).toFixed(1)} L ${(x - 15).toFixed(1)} ${y.toFixed(1)} L ${(x + 15).toFixed(1)} ${y.toFixed(1)} L ${(x + 15).toFixed(1)} ${(padding.top + innerH).toFixed(1)} Z`;
  } else if (points.length > 1) {
    areaPath = `${linePath} L ${points[points.length - 1][0].toFixed(1)} ${(padding.top + innerH).toFixed(1)} L ${points[0][0].toFixed(1)} ${(padding.top + innerH).toFixed(1)} Z`;
  }

  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, class: 'chart-svg', style: 'width:100%;height:100%;min-height:200px;' });
  const gradId = 'grad-area-' + Math.random().toString(36).substr(2, 6);

  const defs = svgEl('defs');
  const grad = svgEl('linearGradient', { id: gradId, x1: '0', y1: '0', x2: '0', y2: '1' });
  grad.appendChild(svgEl('stop', { offset: '0%', 'stop-color': color, 'stop-opacity': '0.45' }));
  grad.appendChild(svgEl('stop', { offset: '100%', 'stop-color': color, 'stop-opacity': '0.02' }));
  defs.appendChild(grad);
  svg.appendChild(defs);

  // Horizontal Grid Lines
  const gridTicks = 4;
  for (let g = 0; g <= gridTicks; g++) {
    const gridY = padding.top + (innerH / gridTicks) * g;
    const tickVal = max - (max / gridTicks) * g;

    svg.appendChild(svgEl('line', {
      x1: padding.left,
      y1: gridY.toFixed(1),
      x2: width - padding.right,
      y2: gridY.toFixed(1),
      stroke: 'rgba(255, 255, 255, 0.08)',
      'stroke-dasharray': '4 4'
    }));

    const yText = svgEl('text', {
      x: (padding.left - 8).toFixed(1),
      y: (gridY + 3).toFixed(1),
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'end',
      fill: '#94A3B8',
    });
    yText.textContent = formatYLabel(tickVal, isCurrency);
    svg.appendChild(yText);
  }

  // Filled Area & Bezier Line
  svg.appendChild(svgEl('path', { d: areaPath, fill: `url(#${gradId})`, stroke: 'none' }));
  svg.appendChild(svgEl('path', { d: linePath, fill: 'none', stroke: color, 'stroke-width': '3', 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }));

  // Secondary curve if provided
  if (values2 && values2.length === values.length) {
    const points2 = values2.map((v, i) => {
      const x = padding.left + stepX * i;
      const y = padding.top + innerH - (max > 0 ? (v / max) * innerH : 0);
      return [x, y];
    });
    const linePath2 = getBezierPath(points2);
    svg.appendChild(svgEl('path', { d: linePath2, fill: 'none', stroke: '#F59E0B', 'stroke-width': '2', 'stroke-dasharray': '4 4' }));
  }

  // Dots with Tooltips
  const dotStep = Math.max(1, Math.floor(values.length / 10));
  points.forEach(([x, y], i) => {
    if (i % dotStep === 0 || i === points.length - 1 || values[i] === maxVal) {
      const isPeak = values[i] === maxVal && maxVal > 0;
      const dot = svgEl('circle', {
        cx: x.toFixed(1),
        cy: y.toFixed(1),
        r: isPeak ? '5.5' : '4',
        fill: isPeak ? '#38BDF8' : '#0B132B',
        stroke: color,
        'stroke-width': isPeak ? '3' : '2',
        style: 'cursor:pointer;'
      });
      const formattedVal = isCurrency ? '₹' + values[i].toLocaleString() : values[i].toLocaleString();
      dot.appendChild(svgEl('title', {})).textContent = `${labels[i]}: ${formattedVal}`;
      svg.appendChild(dot);
    }
  });


  // X Axis Labels
  const labelStep = Math.max(1, Math.ceil(labels.length / 7));
  labels.forEach((label, i) => {
    const isStep = (i % labelStep === 0);
    const isLast = (i === labels.length - 1);
    if (!isStep && !isLast) return;
    if (isLast && !isStep && (i % labelStep === 1 || (i - (i - (i % labelStep))) < 2)) return;

    const text = svgEl('text', {
      x: points[i][0].toFixed(1),
      y: height - 10,
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'middle',
      fill: '#94A3B8',
    });
    text.textContent = cleanAxisLabel(label);
    svg.appendChild(text);
  });

  container.innerHTML = '';
  container.appendChild(svg);
}

export function renderColumnChart(container, { labels, values }, options = {}) {
  if (!container || !values || values.length === 0) return;

  const width = options.width || 420;
  const height = options.height || 220;
  const padding = { top: 22, right: 15, bottom: 35, left: 35 };

  const color1 = options.color1 || '#F97316';
  const color2 = options.color2 || '#EA580C';
  const max = niceMax(Math.max(...values, 0));
  const innerW = width - padding.left - padding.right;
  const innerH = height - padding.top - padding.bottom;

  const gap = values.length > 10 ? 5 : 12;
  const rawBarW = (innerW - gap * (values.length - 1)) / values.length;
  const barW = Math.min(Math.max(rawBarW, 4), 36);
  const groupW = barW * values.length + gap * (values.length - 1);
  const startX = padding.left + Math.max(0, (innerW - groupW) / 2);

  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, class: 'chart-svg', style: 'width:100%;height:100%;min-height:200px;' });
  const gradId = 'grad-col-' + Math.random().toString(36).substr(2, 6);

  const defs = svgEl('defs');
  const grad = svgEl('linearGradient', { id: gradId, x1: '0', y1: '0', x2: '0', y2: '1' });
  grad.appendChild(svgEl('stop', { offset: '0%', 'stop-color': color1 }));
  grad.appendChild(svgEl('stop', { offset: '100%', 'stop-color': color2 }));
  defs.appendChild(grad);
  svg.appendChild(defs);

  // Horizontal Grid Lines
  const gridTicks = 3;
  for (let g = 0; g <= gridTicks; g++) {
    const gridY = padding.top + (innerH / gridTicks) * g;
    const tickVal = max - (max / gridTicks) * g;

    svg.appendChild(svgEl('line', {
      x1: padding.left,
      y1: gridY.toFixed(1),
      x2: width - padding.right,
      y2: gridY.toFixed(1),
      stroke: 'rgba(255, 255, 255, 0.08)',
      'stroke-dasharray': '3 3'
    }));

    const yText = svgEl('text', {
      x: (padding.left - 6).toFixed(1),
      y: (gridY + 3).toFixed(1),
      'font-size': '10',
      'font-weight': '600',
      'text-anchor': 'end',
      fill: '#94A3B8',
    });
    yText.textContent = Math.round(tickVal).toString();
    svg.appendChild(yText);
  }

  // Bars
  const labelStep = Math.max(1, Math.ceil(labels.length / 6));
  values.forEach((v, i) => {
    const barH = max > 0 ? (v / max) * innerH : 0;
    const x = startX + i * (barW + gap);
    const y = padding.top + innerH - barH;

    const rect = svgEl('rect', {
      x: x.toFixed(1),
      y: y.toFixed(1),
      width: barW.toFixed(1),
      height: Math.max(barH, v > 0 ? 3 : 0).toFixed(1),
      rx: '4',
      ry: '4',
      fill: `url(#${gradId})`,
      style: 'cursor:pointer;transition:opacity 0.2s;'
    });
    rect.appendChild(svgEl('title', {})).textContent = `${labels[i]}: ${v} bookings`;
    svg.appendChild(rect);

    if (v > 0 && barH > 14) {
      const valText = svgEl('text', {
        x: (x + barW / 2).toFixed(1),
        y: (y - 4).toFixed(1),
        'font-size': '9',
        'font-weight': '700',
        'text-anchor': 'middle',
        fill: '#F97316'
      });
      valText.textContent = v.toString();
      svg.appendChild(valText);
    }

    const isStep = (i % labelStep === 0);
    const isLast = (i === values.length - 1);
    if (!isStep && !isLast) return;
    if (isLast && !isStep && (i % labelStep === 1 || (i - (i - (i % labelStep))) < 2)) return;

    const text = svgEl('text', {
      x: (x + barW / 2).toFixed(1),
      y: height - 10,
      'font-size': '9',
      'font-weight': '600',
      'text-anchor': 'middle',
      fill: '#94A3B8',
    });
    text.textContent = cleanAxisLabel(labels[i] || '');
    svg.appendChild(text);
  });

  container.innerHTML = '';
  container.appendChild(svg);
}

export function renderMultiColumnChart(container, items, options = {}) {
  if (!container || !items || items.length === 0) return;

  const width = options.width || 360;
  const height = options.height || 220;
  const padding = { top: 25, right: 15, bottom: 35, left: 35 };

  const colors = ['#8B5CF6', '#F97316', '#14B8A6'];
  const max = niceMax(Math.max(...items.map(it => Number(it.count) || 0), 0));
  const innerW = width - padding.left - padding.right;
  const innerH = height - padding.top - padding.bottom;

  const barW = Math.min(38, (innerW - 25 * (items.length - 1)) / items.length);
  const gap = (innerW - barW * items.length) / (items.length + 1);

  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, class: 'chart-svg', style: 'width:100%;height:100%;min-height:180px;' });

  // Grid lines
  for (let g = 0; g <= 3; g++) {
    const gridY = padding.top + (innerH / 3) * g;
    svg.appendChild(svgEl('line', {
      x1: padding.left, y1: gridY.toFixed(1), x2: width - padding.right, y2: gridY.toFixed(1),
      stroke: 'rgba(255,255,255,0.08)', 'stroke-dasharray': '3 3'
    }));
  }

  items.forEach((item, i) => {
    const v = Number(item.count) || 0;
    const barH = max > 0 ? (v / max) * innerH : 0;
    const x = padding.left + gap + i * (barW + gap);
    const y = padding.top + innerH - barH;
    const color = colors[i % colors.length];

    const rect = svgEl('rect', {
      x: x.toFixed(1), y: y.toFixed(1), width: barW.toFixed(1), height: Math.max(barH, 3).toFixed(1),
      rx: '5', ry: '5', fill: color, style: 'cursor:pointer;'
    });
    rect.appendChild(svgEl('title', {})).textContent = `${item.label}: ${v} sessions`;
    svg.appendChild(rect);

    const valText = svgEl('text', {
      x: (x + barW / 2).toFixed(1), y: (y - 5).toFixed(1), 'font-size': '11', 'font-weight': '700',
      'text-anchor': 'middle', fill: color
    });
    valText.textContent = v.toString();
    svg.appendChild(valText);

    const lblText = svgEl('text', {
      x: (x + barW / 2).toFixed(1), y: height - 10, 'font-size': '10', 'font-weight': '600',
      'text-anchor': 'middle', fill: '#94A3B8'
    });
    lblText.textContent = (item.label || '').split(' ')[0];
    svg.appendChild(lblText);
  });

  container.innerHTML = '';
  container.appendChild(svg);
}

export function renderDualRingChart(container, { completionRate = 83.3, successRate = 85.0 }, options = {}) {
  if (!container) return;
  const width = options.width || 220;
  const height = options.height || 210;
  const cx = width / 2;
  const cy = (height - 30) / 2 + 10;

  const r1 = 66;
  const r2 = 48;
  const strokeW = 10;

  const c1 = 2 * Math.PI * r1;
  const c2 = 2 * Math.PI * r2;

  const off1 = c1 * (1 - Math.min(100, Math.max(0, completionRate)) / 100);
  const off2 = c2 * (1 - Math.min(100, Math.max(0, successRate)) / 100);

  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, style: 'width:100%;height:100%;max-height:220px;' });

  // Track 1
  svg.appendChild(svgEl('circle', {
    cx, cy, r: r1, fill: 'none', stroke: 'rgba(255,255,255,0.08)', 'stroke-width': strokeW
  }));
  const arc1 = svgEl('circle', {
    cx, cy, r: r1, fill: 'none', stroke: '#10B981', 'stroke-width': strokeW,
    'stroke-dasharray': c1.toFixed(1), 'stroke-dashoffset': off1.toFixed(1),
    'stroke-linecap': 'round', transform: `rotate(-90 ${cx} ${cy})`,
    style: 'transition:stroke-dashoffset 0.8s ease;'
  });
  arc1.appendChild(svgEl('title', {})).textContent = `Completion Rate: ${completionRate}%`;
  svg.appendChild(arc1);

  // Track 2
  svg.appendChild(svgEl('circle', {
    cx, cy, r: r2, fill: 'none', stroke: 'rgba(255,255,255,0.08)', 'stroke-width': strokeW
  }));
  const arc2 = svgEl('circle', {
    cx, cy, r: r2, fill: 'none', stroke: '#F97316', 'stroke-width': strokeW,
    'stroke-dasharray': c2.toFixed(1), 'stroke-dashoffset': off2.toFixed(1),
    'stroke-linecap': 'round', transform: `rotate(-90 ${cx} ${cy})`,
    style: 'transition:stroke-dashoffset 0.8s ease;'
  });
  arc2.appendChild(svgEl('title', {})).textContent = `Payment Success Rate: ${successRate}%`;
  svg.appendChild(arc2);

  // Center text
  const textVal = svgEl('text', {
    x: cx, y: cy + 1, 'font-size': '18', 'font-weight': '800', 'text-anchor': 'middle', fill: '#F8FAFC'
  });
  textVal.textContent = `${Math.round(completionRate)}%`;
  svg.appendChild(textVal);

  const textSub = svgEl('text', {
    x: cx, y: cy + 15, 'font-size': '9', 'font-weight': '600', 'text-anchor': 'middle', fill: '#94A3B8'
  });
  textSub.textContent = 'Fulfillment';
  svg.appendChild(textSub);

  // Legend
  const legendHtml = `
    <div style="display:flex;justify-content:center;gap:14px;font-size:0.75rem;margin-top:6px;width:100%;">
      <span style="display:inline-flex;align-items:center;gap:4px;color:#94A3B8">
        <span style="width:8px;height:8px;border-radius:50%;background:#10B981"></span>
        <strong style="color:#F8FAFC">${completionRate}%</strong> Fulfillment
      </span>
      <span style="display:inline-flex;align-items:center;gap:4px;color:#94A3B8">
        <span style="width:8px;height:8px;border-radius:50%;background:#F97316"></span>
        <strong style="color:#F8FAFC">${successRate}%</strong> Success
      </span>
    </div>
  `;

  container.innerHTML = '';
  container.appendChild(svg);
  const legendEl = document.createElement('div');
  legendEl.innerHTML = legendHtml;
  container.appendChild(legendEl);
}

export function renderPieChart(container, items, options = {}) {
  if (!container || !items || items.length === 0) return;

  const width = options.width || 240;
  const height = options.height || 210;
  const cx = width / 2;
  const cy = (height - 30) / 2 + 10;
  const radius = Math.min(cx, cy) - 15;

  const total = items.reduce((sum, item) => sum + (Number(item.count || item.value) || 0), 0);
  if (total <= 0) {
    container.innerHTML = '<div style="color:#64748B;font-size:0.8rem;text-align:center;padding:30px">No package data available</div>';
    return;
  }

  const defaultPalette = ['#F59E0B', '#10B981', '#0EA5E9', '#8B5CF6', '#EC4899', '#14B8A6'];
  const colors = options.colors || defaultPalette;

  let currentAngle = 0;
  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, style: 'width:100%;height:100%;max-height:220px;' });

  items.forEach((item, i) => {
    const val = Number(item.count || item.value) || 0;
    if (val <= 0) return;
    const sliceAngle = (val / total) * 360;
    const startAngle = currentAngle;
    const endAngle = currentAngle + sliceAngle;
    currentAngle = endAngle;

    const start = polarToCartesian(cx, cy, radius, endAngle);
    const end = polarToCartesian(cx, cy, radius, startAngle);
    const largeArc = sliceAngle > 180 ? 1 : 0;

    const d = [
      'M', cx, cy,
      'L', start.x.toFixed(1), start.y.toFixed(1),
      'A', radius, radius, 0, largeArc, 0, end.x.toFixed(1), end.y.toFixed(1),
      'Z'
    ].join(' ');

    const sliceColor = colors[i % colors.length];
    const path = svgEl('path', {
      d,
      fill: sliceColor,
      stroke: options.bg || '#141E33',
      'stroke-width': '2.5',
      style: 'cursor:pointer;transition:transform 0.2s ease, opacity 0.2s;',
    });
    const percent = Math.round((val / total) * 100);
    path.appendChild(svgEl('title', {})).textContent = `${item.label || item.name}: ${val} (${percent}%)`;
    svg.appendChild(path);
  });

  // Legend
  const legendHtml = items.map((item, i) => {
    const val = Number(item.count || item.value) || 0;
    const percent = total > 0 ? Math.round((val / total) * 100) : 0;
    const color = colors[i % colors.length];
    return `<div style="display:inline-flex;align-items:center;gap:5px;font-size:0.75rem;color:#94A3B8;margin-right:10px;margin-top:4px;">
      <span style="width:8px;height:8px;border-radius:2px;background:${color};display:inline-block"></span>
      <span style="color:#F8FAFC;font-weight:600">${item.label || item.name}</span>
      <span style="color:#94A3B8">(${percent}%)</span>
    </div>`;
  }).join('');

  container.innerHTML = '';
  container.appendChild(svg);
  const legendContainer = document.createElement('div');
  legendContainer.style.cssText = 'display:flex;flex-wrap:wrap;justify-content:center;margin-top:6px;width:100%;';
  legendContainer.innerHTML = legendHtml;
  container.appendChild(legendContainer);
}

export function renderDonutChart(container, items, options = {}) {
  if (!container || !items || items.length === 0) return;

  const width = options.width || 240;
  const height = options.height || 210;
  const cx = width / 2;
  const cy = (height - 30) / 2 + 10;
  const outerRadius = Math.min(cx, cy) - 15;
  const innerRadius = outerRadius * 0.62;

  const total = items.reduce((sum, item) => sum + (Number(item.count || item.value || item.total) || 0), 0);
  if (total <= 0) {
    container.innerHTML = '<div style="color:#64748B;font-size:0.8rem;text-align:center;padding:30px">No gateway data available</div>';
    return;
  }

  const defaultPalette = ['#10B981', '#3B82F6', '#F97316', '#8B5CF6', '#F59E0B'];
  const colors = options.colors || defaultPalette;

  let currentAngle = 0;
  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, style: 'width:100%;height:100%;max-height:220px;' });

  items.forEach((item, i) => {
    const val = Number(item.count || item.value || item.total) || 0;
    if (val <= 0) return;
    const sliceAngle = (val / total) * 360;
    const startAngle = currentAngle;
    const endAngle = currentAngle + sliceAngle;
    currentAngle = endAngle;

    const p1 = polarToCartesian(cx, cy, outerRadius, endAngle);
    const p2 = polarToCartesian(cx, cy, outerRadius, startAngle);
    const p3 = polarToCartesian(cx, cy, innerRadius, startAngle);
    const p4 = polarToCartesian(cx, cy, innerRadius, endAngle);
    const largeArc = sliceAngle > 180 ? 1 : 0;

    const d = [
      'M', p1.x.toFixed(1), p1.y.toFixed(1),
      'A', outerRadius, outerRadius, 0, largeArc, 0, p2.x.toFixed(1), p2.y.toFixed(1),
      'L', p3.x.toFixed(1), p3.y.toFixed(1),
      'A', innerRadius, innerRadius, 0, largeArc, 1, p4.x.toFixed(1), p4.y.toFixed(1),
      'Z'
    ].join(' ');

    const sliceColor = colors[i % colors.length];
    const path = svgEl('path', {
      d,
      fill: sliceColor,
      stroke: options.bg || '#141E33',
      'stroke-width': '2',
      style: 'cursor:pointer;transition:transform 0.2s ease, opacity 0.2s;',
    });
    const percent = Math.round((val / total) * 100);
    path.appendChild(svgEl('title', {})).textContent = `${item.label || item.name}: ${val} (${percent}%)`;
    svg.appendChild(path);
  });

  const centerText1 = svgEl('text', {
    x: cx, y: cy - 2, 'font-size': '15', 'font-weight': '700', 'text-anchor': 'middle', fill: '#F8FAFC'
  });
  centerText1.textContent = options.centerLabel || total.toString();
  svg.appendChild(centerText1);

  const centerText2 = svgEl('text', {
    x: cx, y: cy + 14, 'font-size': '10', 'font-weight': '600', 'text-anchor': 'middle', fill: '#94A3B8'
  });
  centerText2.textContent = options.centerSub || 'Total';
  svg.appendChild(centerText2);

  // Legend
  const legendHtml = items.map((item, i) => {
    const val = Number(item.count || item.value || item.total) || 0;
    const percent = total > 0 ? Math.round((val / total) * 100) : 0;
    const color = colors[i % colors.length];
    return `<div style="display:inline-flex;align-items:center;gap:5px;font-size:0.75rem;color:#94A3B8;margin-right:10px;margin-top:4px;">
      <span style="width:8px;height:8px;border-radius:2px;background:${color};display:inline-block"></span>
      <span style="color:#F8FAFC;font-weight:600">${item.label || item.name}</span>
      <span style="color:#94A3B8">(${percent}%)</span>
    </div>`;
  }).join('');

  container.innerHTML = '';
  container.appendChild(svg);
  const legendContainer = document.createElement('div');
  legendContainer.style.cssText = 'display:flex;flex-wrap:wrap;justify-content:center;margin-top:6px;width:100%;';
  legendContainer.innerHTML = legendHtml;
  container.appendChild(legendContainer);
}

export function renderSparkTrendChart(container, { labels, values }, options = {}) {
  if (!container || !values || values.length === 0) return;

  const width = options.width || 600;
  const height = options.height || 190;
  const padding = { top: 20, right: 20, bottom: 30, left: 45 };

  const color = options.color || '#10B981';
  const max = niceMax(Math.max(...values, 0));
  const innerW = width - padding.left - padding.right;
  const innerH = height - padding.top - padding.bottom;
  const stepX = values.length > 1 ? innerW / (values.length - 1) : 0;

  const points = values.map((v, i) => {
    const x = padding.left + stepX * i;
    const y = padding.top + innerH - (max > 0 ? (v / max) * innerH : 0);
    return [x, y];
  });

  const linePath = getBezierPath(points);
  let areaPath = '';
  if (points.length === 1) {
    const x = points[0][0];
    const y = points[0][1];
    areaPath = `M ${(x - 15).toFixed(1)} ${(padding.top + innerH).toFixed(1)} L ${(x - 15).toFixed(1)} ${y.toFixed(1)} L ${(x + 15).toFixed(1)} ${y.toFixed(1)} L ${(x + 15).toFixed(1)} ${(padding.top + innerH).toFixed(1)} Z`;
  } else if (points.length > 1) {
    areaPath = `${linePath} L ${points[points.length - 1][0].toFixed(1)} ${(padding.top + innerH).toFixed(1)} L ${points[0][0].toFixed(1)} ${(padding.top + innerH).toFixed(1)} Z`;
  }

  const svg = svgEl('svg', { viewBox: `0 0 ${width} ${height}`, class: 'chart-svg', style: 'width:100%;height:100%;min-height:170px;' });
  const gradId = 'grad-spark-' + Math.random().toString(36).substr(2, 6);

  const defs = svgEl('defs');
  const grad = svgEl('linearGradient', { id: gradId, x1: '0', y1: '0', x2: '0', y2: '1' });
  grad.appendChild(svgEl('stop', { offset: '0%', 'stop-color': color, 'stop-opacity': '0.35' }));
  grad.appendChild(svgEl('stop', { offset: '100%', 'stop-color': color, 'stop-opacity': '0.01' }));
  defs.appendChild(grad);
  svg.appendChild(defs);

  for (let g = 0; g <= 3; g++) {
    const gridY = padding.top + (innerH / 3) * g;
    const tickVal = max - (max / 3) * g;

    svg.appendChild(svgEl('line', {
      x1: padding.left, y1: gridY.toFixed(1), x2: width - padding.right, y2: gridY.toFixed(1),
      stroke: 'rgba(255,255,255,0.06)', 'stroke-dasharray': '3 3'
    }));

    const yText = svgEl('text', {
      x: (padding.left - 8).toFixed(1), y: (gridY + 3).toFixed(1), 'font-size': '9', 'font-weight': '600',
      'text-anchor': 'end', fill: '#94A3B8'
    });
    yText.textContent = Math.round(tickVal).toString();
    svg.appendChild(yText);
  }

  svg.appendChild(svgEl('path', { d: areaPath, fill: `url(#${gradId})`, stroke: 'none' }));
  svg.appendChild(svgEl('path', { d: linePath, fill: 'none', stroke: color, 'stroke-width': '2.5', 'stroke-linecap': 'round' }));

  const dotStep = Math.max(1, Math.floor(values.length / 8));
  points.forEach(([x, y], i) => {
    if (i % dotStep === 0 || i === points.length - 1) {
      const dot = svgEl('circle', {
        cx: x.toFixed(1), cy: y.toFixed(1), r: '3.5', fill: '#0B132B', stroke: color, 'stroke-width': '2',
        style: 'cursor:pointer;'
      });
      dot.appendChild(svgEl('title', {})).textContent = `${labels[i]}: ${values[i]} clients`;
      svg.appendChild(dot);
    }
  });

  const labelStep = Math.max(1, Math.ceil(labels.length / 6));
  labels.forEach((label, i) => {
    const isStep = (i % labelStep === 0);
    const isLast = (i === labels.length - 1);
    if (!isStep && !isLast) return;
    if (isLast && !isStep && (i % labelStep === 1 || (i - (i - (i % labelStep))) < 2)) return;

    const text = svgEl('text', {
      x: points[i][0].toFixed(1), y: height - 8, 'font-size': '9', 'font-weight': '600',
      'text-anchor': 'middle', fill: '#94A3B8'
    });
    text.textContent = cleanAxisLabel(label);
    svg.appendChild(text);
  });

  container.innerHTML = '';
  container.appendChild(svg);
}
