import React, { Component } from 'react';
import AlternateNavbar from '../components/shared/AlternateNavbar';
import 'simple-react-notifications/dist/index.css'
import 'react-phone-number-input/style.css'

export default (Page) => {
  return class extends Component {
    render () {
      return (
        <div id="treten">
          <AlternateNavbar />
          <Page {...this.props} />
        </div>
      )
    }
  };
};
