import React from 'react';
import Link from 'next/link';
import { Icon, Button } from 'antd';
import { Input } from 'antd';
const { Search } = Input;
import Brand from './Brand';

import 'bootstrap/dist/css/bootstrap.min.css';

import {
  Collapse,
  Navbar,
  NavbarBrand,
  NavbarToggler,
  Nav,
  NavItem,
  UncontrolledDropdown,
  DropdownToggle,
  DropdownMenu,
  DropdownItem } from 'reactstrap';


class Navigation extends React.Component {

  constructor(props) {
    super(props);
    this.toggle = this.toggle.bind(this);
    this.state = {
      isOpen: false
    };
  }
  toggle() {
    this.setState({
      isOpen: !this.state.isOpen
    });
  }

  render () {
    return (
      <Navbar 
        className="fixed-top" 
        style={
          this.props.noBoxShadow 
            ? { 
                boxShadow: 'none', 
                border: '1px solid #F0F1F3' 
              } 
            : {} 
          } 
        color="faded" 
        light 
        expand="md"
      >
        <div className="container">
          <NavbarBrand href="/">
            <Brand />
          </NavbarBrand>

          <NavbarToggler onClick={this.toggle} />
          <Collapse isOpen={this.state.isOpen} navbar>
            <Nav className="mr-auto">
              <div className="is-flex is-vcentered">
                <Search
                  size="large"
                  placeholder="What do you want to learn?"
                  onSearch={value => console.log(value)}
                  style={{ width: 250 }}
                />
              </div>
            </Nav>
            <Nav className="ml-auto" navbar>
              <UncontrolledDropdown nav inNavbar>
                <DropdownToggle nav>
                  Courses
                  <Icon type="down" />
                </DropdownToggle>
                <DropdownMenu right>
                  <DropdownItem>
                    Option 1
                  </DropdownItem>
                  <DropdownItem>
                    Option 2
                  </DropdownItem>
                  <DropdownItem divider />
                  <DropdownItem>
                    Reset
                  </DropdownItem>
                </DropdownMenu>
              </UncontrolledDropdown>
              <NavItem>
                <Link href="/about-us/">About us</Link>
              </NavItem>
              <UncontrolledDropdown nav inNavbar>
                <DropdownToggle nav>
                  Why us
                  <Icon type="down" />
                </DropdownToggle>
                <DropdownMenu right>
                  <DropdownItem>
                    Option 1
                  </DropdownItem>
                  <DropdownItem>
                    Option 2
                  </DropdownItem>
                  <DropdownItem divider />
                  <DropdownItem>
                    Reset
                  </DropdownItem>
                </DropdownMenu>
              </UncontrolledDropdown>
              <NavItem>
                <Link href="/blog">
                  <a>Blog</a>
                </Link>
              </NavItem>
              <NavItem>
                <Link href="/contact-us">
                  <a>Contact</a>
                </Link>
              </NavItem>
              <NavItem>
                <Link href="/auth">
                  <Button 
                    className="ml-3" 
                    size="large" 
                    type="danger"
                  >
                    Register/Log in
                  </Button>
                </Link>
              </NavItem>
            </Nav>
          </Collapse>
          </div>
        </Navbar>
    );
  }
}

export default Navigation;
