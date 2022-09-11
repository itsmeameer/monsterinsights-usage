/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const {
  __
} = wp.i18n;
const {
  registerBlockType
} = wp.blocks;
const {
  RawHTML,
  Fragment
} = wp.element;
const {
  InspectorControls
} = wp.blockEditor;
const {
  PanelBody,
  CheckboxControl
} = wp.components;
const MIUsage = monsterinsights_usage_table_block_object;

const getData = async action => {
  const URL = `${MIUsage.ajax_url}?action=${action}&nonce=${MIUsage.nonce}`;
  const request = await fetch(URL, {
    method: "GET"
  });

  if (request.status !== 200) {
    throw new Error(__('Something went wrong. Could not fetch new data.', 'monsterinsights-usage'));
  }

  return request.json();
};

const showCol = (col, setting) => {
  if (typeof col === 'string') {
    const keys = ["id", "fname", "lname", "email", "date"];
    col = keys.indexOf(col);
  }

  return setting[col] === true;
};

registerBlockType('monsterinsights-usage/table-block', {
  title: __('MonsterInsights Usage Table', 'monsterinsights-usage'),
  description: __('A simple block that shows takes MonsterInsights usage stats from an API and shows them in a table', 'monsterinsights-usage'),
  icon: 'editor-table',
  category: 'widgets',
  keywords: [__('MonsterInsights', 'monsterinsights-usage'), __('Usage', 'monsterinsights-usage'), __('Table', 'monsterinsights-usage'), __('API', 'monsterinsights-usage')],
  supports: {
    multiple: true
  },
  attributes: {
    APIData: {
      type: 'object'
    },
    showTitle: {
      type: 'boolean'
    },
    showColID: {
      type: 'boolean'
    },
    showColFirstName: {
      type: 'boolean'
    },
    showColLastName: {
      type: 'boolean'
    },
    showColEmail: {
      type: 'boolean'
    },
    showColDate: {
      type: 'boolean'
    }
  },

  edit(_ref) {
    let {
      attributes,
      setAttributes,
      className
    } = _ref;

    if (!attributes.APIData) {
      getData('monsterinsights_usage_api_data').then(data => {
        setAttributes({
          APIData: data
        });
      });
    }

    const {
      showTitle = true,
      showColID = false,
      showColFirstName = true,
      showColLastName = true,
      showColEmail = true,
      showColDate = true
    } = attributes;
    const colSettings = [showColID, showColFirstName, showColLastName, showColEmail, showColDate];
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
      title: __('Settings', 'monsterinsights-usage'),
      initialOpen: true
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
      label: __('Show Title?', 'monsterinsights-usage'),
      checked: showTitle,
      onChange: isChecked => setAttributes({
        showTitle: isChecked
      })
    })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(PanelBody, {
      title: __('Columns', 'monsterinsights-usage'),
      initialOpen: false
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
      label: __('ID', 'monsterinsights-usage'),
      checked: showColID,
      onChange: isChecked => setAttributes({
        showColID: isChecked
      })
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
      label: __('First Name', 'monsterinsights-usage'),
      checked: showColFirstName,
      onChange: isChecked => setAttributes({
        showColFirstName: isChecked
      })
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
      label: __('Last Name', 'monsterinsights-usage'),
      checked: showColLastName,
      onChange: isChecked => setAttributes({
        showColLastName: isChecked
      })
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
      label: __('Email', 'monsterinsights-usage'),
      checked: showColEmail,
      onChange: isChecked => setAttributes({
        showColEmail: isChecked
      })
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(CheckboxControl, {
      label: __('Date', 'monsterinsights-usage'),
      checked: showColDate,
      onChange: isChecked => setAttributes({
        showColDate: isChecked
      })
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: className
    }, attributes.APIData ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(Fragment, null, showTitle ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h3", {
      className: "monsterinsights-usage-title"
    }, attributes.APIData.title) : null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      class: "wp-block-table"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("table", {
      className: "monsterinsights-usage-table"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("thead", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, Object.keys(attributes.APIData.data.headers).map((th, index) => {
      return showCol(index, colSettings) ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("th", null, attributes.APIData.data.headers[index]) : '';
    }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tbody", null, Object.keys(attributes.APIData.data.rows).map(row => {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("tr", null, Object.keys(attributes.APIData.data.rows[row]).map(col => {
        return showCol(col, colSettings) ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("td", null, attributes.APIData.data.rows[row][col]) : '';
      }));
    }))))) : __('Loading...', 'monsterinsights-usage')));
  },

  save(_ref2) {
    let {
      attributes
    } = _ref2;
    const {
      showTitle = true,
      showColID = false,
      showColFirstName = true,
      showColLastName = true,
      showColEmail = true,
      showColDate = true
    } = attributes; // Generate shortcode attributes.

    const myShortcode = '[mi-usage-table show_title="' + showTitle + '" show_col_id="' + showColID + '" show_col_first_name="' + showColFirstName + '" show_col_last_name="' + showColLastName + '" show_col_email="' + showColEmail + '" show_col_date="' + showColDate + '"]';
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(RawHTML, null, myShortcode));
  }

});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map