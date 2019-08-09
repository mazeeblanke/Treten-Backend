import React, { Component } from 'react';
import AlternateNavbar from '../components/shared/AlternateNavbar';

export default (Page) => {
  return class extends Component {

    // static async getInitialProps (ctx) {
    //   let pageProps = {}
    //   if (Page.getInitialProps) {
    //     pageProps = await Page.getInitialProps(ctx);
    //   }

    //   return { ...pageProps };
    // }

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
