import React, { Component } from 'react';
import Navbar from '../../components/shared/Navbar';
import 'simple-react-notifier/dist/index.css'

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
          <Navbar noBoxShadow {...this.props} />
          <Page {...this.props} />
        </div>
      )
    }
  };
};
