/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
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
 * PrimeUI Tree widget, extended version with the following new features
 * OPTIONS:
 * - controller: name of the controller to call for tree nodes loading 
 * - action: name of the controller action to call for tree nodes loading 
 * - autoSelectFamily: select parents and children of a tree node when selected
 * METHODS:
 * - getSelection: return selected tree nodes as an array
 * - selectNodes: select tree nodes from an array
 * - selectNode: overloaded version, implements the option 'autoSelectFamily' 
 *
 * File version: 1.1
 * Last update: 02/04/2017 
 */
$(function () {
    $.widget("znetdk.zdktree", $.primeui.puitree, {
        options: {
            /** PHP controller name */
            controller: null,
            /** PHP action name */
            action: null,
            /** Select automatically the parents and children of the node is
             *  selected on a mouse click */ 
            autoSelectFamily: false
        },
        /**
         * Overloads the parent's constructor to load tree nodes from JSON
         * data sent by the specified controller action 
         */
        _create: function () {
            this.element.addClass('zdk-tree'); /* Class searched by zdkform */
            if (this.options.controller && this.options.action) {
                this.options.nodes = function (ui, response) {
                    var $this = this;
                    znetdk.request({
                        control: $this.options.controller,
                        action: $this.options.action,
                        callback: function (data) {
                            response.call($this, data.treenodes);
                            $this._trigger('dataloaded');
                        }
                    });
                };
            }
            this._super(); // The parent contructor is called...
        },
        /**
         * Overload of the original puitree widget method: select the parent
         * and children of the selected node if option autoSelectFamily is set
         * to true.  
         * @param {jQuery object} node Node to be selected.
         * @param {boolean} noChildren Are chidren to be selected?
         * @returns {undefined}
         */
        selectNode: function(node, noChildren) {
            this._super(node);
            if (this.options.autoSelectFamily) {
                var parentNode = this._getParentNode(node);
                if (parentNode !== null && this._isNodeSelected(parentNode.data('puidata')) === false) {
                    this.selectNode(parentNode,true);
                }
                var childNodes = this._getChildNodes(node);
                if (childNodes !== null && !noChildren) {
                    var $this = this;
                    childNodes.each(function() {
                        if ($this._isNodeSelected($(this).data('puidata')) === false) {
                            $this.selectNode($(this));                            
                        }
                    });
                }
            }
        },
        /**
         * Return an array of the select nodes in the tree. This method is 
         * called by the zdkform widget.
         * @returns {Array} Array of objects where the property 'name' is 
         * initialized with the name specified in the attribute "data-name"
         * specified for the tree widget. 
         */
        getSelection: function () {
            var selectedNodes = new Array();
            var elementName = this.element.attr("data-name");
            for (var i = 0; i < this.selection.length; i++) {
                var node = {name: elementName, value: this.selection[i]};
                selectedNodes.push(node);
            }
            return selectedNodes;
        },
        /**
         * Select the tree nodes from an array of node IDs. This method is 
         * called by the zdkform widget.
         * @param {array} treeNodes node identifiers to select in the tree.
         * @returns {undefined}
         */
        selectNodes: function (treeNodes) {
            if ($.type(treeNodes) === 'array') {
                var $this = this;
                var treeNodesToSelect = treeNodes;
                this.element.find('.pui-treenode').each(function () {
                    var nodeData = $(this).data('puidata');
                    var dataIndex = treeNodesToSelect.indexOf(nodeData);
                    if (dataIndex !== -1) {
                        $this.selectNode($(this),true);
                    }
                });
            } else {
                throw 'Unsupported type. treeNodes parameter must be an array';
            }
        },
        /**
         * Returns the parent node of the specified child node.
         * @param {type} node jQuery element of the node for which the parent 
         * element is to be returned.
         * @returns {jQuery object|null} jQuery object of the parent element 
         * found for the specified node. Null is returned if no parent node is found.
         */
        _getParentNode: function(node) {
            var parentNode = node.parent().parent();
            if (parentNode.hasClass('pui-treenode-parent')) {
                return parentNode;
            } else {
                return null;
            }
        },
        /**
         * Return the children of the specified parent node
         * @param {jQuery object} node Parent node
         * @returns {jQuery object array|null} Children of the parent node
         */
        _getChildNodes: function(node) {
            if (node.hasClass('pui-treenode-parent')) {
                return node.children('ul.pui-treenode-children').children('li.pui-treenode');
            } else {
                return null;
            }
        }
    });
});