<?php

namespace Gestion\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Gestion\PreinscriptionBundle\Entity\preinscription;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionAdminBundle:Default:index.html.twig');
    }

    public function mailingAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('jeap37208@gmail.com')
            ->setTo('samifereh@gmail.com')
            ->setBody(
                'You have been registered in MedicalGuide system as a doctor. Username: '
            )

            ->addPart(
                $this->renderView(
                    'GestionAdminBundle:Default:infos.text.twig',
                    array('name' => 'sami')
                ),
                'text/plain'
            )

        ;
        $this->get('mailer')->send($message);
        return $this->render('GestionAdminBundle:Default:default.html.twig');
           }

    public function defaultAction()
    {
        return $this->render('GestionAdminBundle:Default:default.html.twig');
    }

    public function dashbord2Action()
    {
        return $this->render('GestionAdminBundle:Default:dashbord2.html.twig');
    }

    public function dashbord3Action()
    {
        return $this->render('GestionAdminBundle:Default:dashbord3.html.twig');
    }

    public function ui_colorsAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_colors.html.twig');
    }

    public function ui_metronic_gridAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_metronic_grid.html.twig');
    }

    public function ui_generalAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_general.html.twig');
    }

    public function ui_buttonsAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_buttons.html.twig');
    }

    public function ui_buttons_spinnerAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_buttons_spinner.html.twig');
    }

    public function ui_confirmationsAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_confirmations.html.twig');
    }

    public function ui_sweetalertAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_sweetalert.html.twig');
    }

    public function ui_iconsAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_icons.html.twig');
    }

    public function ui_sociconsAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_socicons.html.twig');
    }

    public function ui_typographyAction()
    {
        return $this->render('GestionAdminBundle:Default:ui_typography.html.twig');
    }

    public function components_date_time_pickersAction()
    {
        return $this->render('GestionAdminBundle:Default:components_date_time_pickers.html.twig');
    }

    public function components_color_pickersAction()
    {
        return $this->render('GestionAdminBundle:Default:components_color_pickers.html.twig');
    }

    public function components_select2Action()
    {
        return $this->render('GestionAdminBundle:Default:components_select2.html.twig');
    }

    public function components_bootstrap_multiselect_dropdownAction()
    {
        return $this->render('GestionAdminBundle:Default:components_bootstrap_multiselect_dropdown.html.twig');
    }

    public function components_bootstrap_selectAction()
    {
        return $this->render('GestionAdminBundle:Default:components_bootstrap_select.html.twig');
    }

    public function components_multi_selectAction()
    {
        return $this->render('GestionAdminBundle:Default:components_multi_select.html.twig');
    }

    public function components_bootstrap_select_splitterAction()
    {
        return $this->render('GestionAdminBundle:Default:components_bootstrap_select_splitter.html.twig');
    }

    public function components_clipboardAction()
    {
        return $this->render('GestionAdminBundle:Default:components_clipboard.html.twig');
    }

    public function components_bootstrap_tagsinputAction()
    {
        return $this->render('GestionAdminBundle:Default:components_bootstrap_tagsinput.html.twig');
    }

    public function components_bootstrap_switchAction()
    {
        return $this->render('GestionAdminBundle:Default:components_bootstrap_switch.html.twig');
    }

    public function form_controlsAction()
    {
        return $this->render('GestionAdminBundle:Default:form_controls.html.twig');
    }

    public function form_controls_mdAction()
    {
        return $this->render('GestionAdminBundle:Default:form_controls_md.html.twig');
    }

    public function form_validationAction()
    {
        return $this->render('GestionAdminBundle:Default:form_validation.html.twig');
    }

    public function form_validation_states_mdAction()
    {
        return $this->render('GestionAdminBundle:Default:form_validation_states_md.html.twig');
    }

    public function form_validation_mdAction()
    {
        return $this->render('GestionAdminBundle:Default:form_validation_md.html.twig');
    }

    public function form_layoutsAction()
    {
        return $this->render('GestionAdminBundle:Default:form_layouts.html.twig');
    }

    public function form_repeaterAction()
    {
        return $this->render('GestionAdminBundle:Default:form_repeater.html.twig');
    }

    public function form_input_maskAction()
    {
        return $this->render('GestionAdminBundle:Default:form_input_mask.html.twig');
    }

    public function form_editableAction()
    {
        return $this->render('GestionAdminBundle:Default:form_editable.html.twig');
    }

    public function portlet_boxedAction()
    {
        return $this->render('GestionAdminBundle:Default:portlet_boxed.html.twig');
    }

    public function portlet_lightAction()
    {
        return $this->render('GestionAdminBundle:Default:portlet_light.html.twig');
    }

    public function portlet_solidAction()
    {
        return $this->render('GestionAdminBundle:Default:portlet_solid.html.twig');
    }

    public function portlet_ajaxAction() 
    {
        return $this->render('GestionAdminBundle:Default:portlet_ajax.html.twig');
    }

    public function portlet_draggableAction()
    {
        return $this->render('GestionAdminBundle:Default:portlet_draggable.html.twig');
    }

    public function maps_googleAction()
    {
        return $this->render('GestionAdminBundle:Default:maps_google.html.twig');
    }


















































































}
