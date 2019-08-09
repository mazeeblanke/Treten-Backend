import React from 'react';
import Brand from './Brand';
import {
  Navbar,
  NavbarBrand,
} from 'reactstrap';

const AlternateNavbar = () => {
  return (
    <Navbar className="no-box-shadow light-border-bottom" color="faded" light expand="md">
      <div className="container d-flex justify-content-center">
        <Brand />
      </div>
    </Navbar>
  );
};

export default AlternateNavbar;