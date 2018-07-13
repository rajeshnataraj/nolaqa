<?php
/**
 * Copyright (C) 2011-2013 Graham Breach
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * For more information, please contact <graham@goat1000.com>
 */

require_once 'SVGGraphLineGraph.php';
require_once 'SVGGraphMultiGraph.php';

/**
 * MultiLineGraph - joined line, with axes and grid
 */
class MultiLineGraph extends LineGraph {

  protected $require_integer_keys = false;
  protected $multi_graph;

  protected function Draw()
  {
    $body = $this->Grid() . $this->Guidelines(SVGG_GUIDELINE_BELOW);

    $plots = '';
    $y_axis_pos = $this->height - $this->pad_bottom - $this->y0;
    $y_bottom = min($y_axis_pos, $this->height - $this->pad_bottom);

    $ccount = count($this->colours);
    $chunk_count = count($this->multi_graph);
    for($i = 0; $i < $chunk_count; ++$i) {
      $bnum = 0;
      $cmd = 'M';
      $path = $fillpath = '';
      $attr = array('fill' => 'none');
      $fill = $this->multi_graph->Option($this->fill_under, $i);
      $dash = $this->multi_graph->Option($this->line_dash, $i);
      $stroke_width = 
        $this->multi_graph->Option($this->line_stroke_width, $i);
      if(!empty($dash))
        $attr['stroke-dasharray'] = $dash;
      $attr['stroke-width'] = $stroke_width <= 0 ? 1 : $stroke_width;

      foreach($this->multi_graph[$i] as $item) {
        $x = $this->GridPosition($item->key, $bnum);
        if(!is_null($x) && !is_null($item->value)) {
          $y = $y_axis_pos - ($item->value * $this->bar_unit_height);

          if($fill && empty($fillpath))
            $fillpath = "M$x {$y_bottom}L";
          $path .= "$cmd$x $y ";
          $fillpath .= "$x $y ";

          // no need to repeat same L command
          $cmd = $cmd == 'M' ? 'L' : '';
          $this->AddMarker($x, $y, $item, NULL, $i);
          $last_x = $x;
        }
        ++$bnum;
      }

      if(!empty($path)) {
        $attr['d'] = $path;
        $attr['stroke'] = $this->GetColour(null, $i % $ccount, true);
        $graph_line = $this->Element('path', $attr);
        $fill_style = null;

        if($fill) {
          $opacity = $this->multi_graph->Option($this->fill_opacity, $i);
          $fillpath .= "L{$last_x} {$y_bottom}z";
          $fill_style = array(
            'fill' => $this->GetColour(null, $i % $ccount),
            'd' => $fillpath,
            'stroke' => $attr['fill'],
          );
          if($opacity < 1)
            $fill_style['opacity'] = $opacity;
          $graph_line = $this->Element('path', $fill_style) . $graph_line;
        }
        $plots .= $graph_line;

        unset($attr['d']);
        $this->line_styles[] = $attr;
        $this->fill_styles[] = $fill_style;
      }
    }

    $group = array();
    $this->ClipGrid($group);
    $body .= $this->Element('g', $group, NULL, $plots);
    $body .= $this->Guidelines(SVGG_GUIDELINE_ABOVE);
    $body .= $this->Axes();
    $body .= $this->CrossHairs();
    $body .= $this->DrawMarkers();
    return $body;
  }

  /**
   * construct multigraph
   */
  public function Values($values)
  {
    parent::Values($values);
    if(!$this->values->error)
      $this->multi_graph = new MultiGraph($this->values, $this->force_assoc,
        $this->require_integer_keys);
  }


  /**
   * The horizontal count is reduced by one
   */
  protected function GetHorizontalCount()
  {
    return $this->multi_graph->ItemsCount();
  }

  /**
   * Returns the maximum value
   */
  protected function GetMaxValue()
  {
    return $this->multi_graph->GetMaxValue();
  }

  /**
   * Returns the minimum value
   */
  protected function GetMinValue()
  {
    return $this->multi_graph->GetMinValue();
  }

  protected function GetMaxKey()
  {
    return $this->multi_graph->GetMaxKey();
  }

  protected function GetMinKey()
  {
    return $this->multi_graph->GetMinKey();
  }

  /**
   * Returns the key from the MultiGraph
   */
  protected function GetKey($index)
  {
    return $this->multi_graph->GetKey($index);
  }

}

