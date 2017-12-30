/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2016 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * InputHtml : HTML input widget
 *
 * File version: 1.0
 * Last update: 05/13/2016
 */

/**
 * zdkinputhtml widget
 * Editing HTML text in a data form 
 */
$.widget("znetdk.zdkinputhtml", {
    _create: function() {
        var input = this.element,
            disabled = input.attr('data-disabled');
        //visuals
        input.addClass('pui-inputtext ui-widget ui-state-default ui-corner-all');
        if (disabled === 'true') {
            input.addClass('ui-state-disabled');
            this.element.attr('contenteditable',false);
        } else {
            this.element.attr('contenteditable',true);
            this._enableMouseEffects();
        }

        //aria
        input.attr('role', 'textbox').attr('aria-disabled', disabled)
            /*.attr('aria-readonly', input.prop('readonly'))*/
            .attr('aria-multiline', true);
    },
    _enableMouseEffects: function () {
        var input = this.element, $this = this;
        this.element.on('mouseenter.zdkinputhtml mouseleave.zdkinputhtml', function () {
            input.toggleClass('ui-state-hover');
        }).on('focus.zdkinputhtml', function () {
            input.addClass('ui-state-focus');
            // The Html content is memorized to evaluate changes...
            $this.htmlContent = $this.getValue();
        }).on('blur.zdkinputhtml', function () {
            input.removeClass('ui-state-focus');
            // Checks if the Html content has changed...
            if ($this.htmlContent !== $this.getValue()) {
                $this._trigger('change');
            }
        });
    },
    _disableMouseEffects: function () {
        var input = this.element;
        input.off( "mouseenter.zdkinputhtml mouseleave.zdkinputhtml focus.zdkinputhtml blur.zdkinputhtml");
    },
    disable: function () {
        if (this.element.attr('data-disabled') === 'false' || this.element.attr('data-disabled') === undefined) {
            this.element.attr('data-disabled', true);
            this.element.attr('aria-disabled', true);
            this.element.attr('contenteditable',false);
            this.element.addClass('ui-state-disabled');
            this.element.removeClass('ui-state-focus ui-state-hover');
            this._disableMouseEffects();
        }
    },
    enable: function () {
        if (this.element.attr('data-disabled') === 'true') {
            this.element.attr('data-disabled', false);
            this.element.attr('aria-disabled', false);
            this.element.attr('contenteditable',true);
            this.element.removeClass('ui-state-disabled');
            this._enableMouseEffects();
        }
    },
    getValue: function() {
        return this.element.html();
    },
    setValue: function(value) {
        this.element.html(value);
    },
    empty: function() {
        this.element.empty();
    },
    isEmpty: function() {
        return this.element.text().length === 0;
    }

});