package com.gmail.eero;

import com.vaadin.flow.component.Key;
import com.vaadin.flow.component.UI;
import com.vaadin.flow.component.button.Button;
import com.vaadin.flow.component.button.ButtonVariant;
import com.vaadin.flow.component.combobox.ComboBox;
import com.vaadin.flow.component.dependency.CssImport;
import com.vaadin.flow.component.dependency.JavaScript;
import com.vaadin.flow.component.html.Div;
import com.vaadin.flow.component.html.Label;
import com.vaadin.flow.component.notification.Notification;
import com.vaadin.flow.component.orderedlayout.VerticalLayout;
import com.vaadin.flow.component.page.Page;
import com.vaadin.flow.component.textfield.TextField;
import com.vaadin.flow.dom.Element;
import com.vaadin.flow.router.Route;
import com.vaadin.flow.server.PWA;
import org.springframework.beans.factory.annotation.Autowired;


/**
 * A sample Vaadin view class.
 * <p>
 * To implement a Vaadin view just extend any Vaadin component and
 * use @Route annotation to announce it in a URL as a Spring managed
 * bean.
 * Use the @PWA annotation make the application installable on phones,
 * tablets and some desktop browsers.
 * <p>
 * A new instance of this class is created for every new user and every
 * browser tab/window.
 */
@Route
@PWA(name = "Vaadin Application",
        shortName = "Vaadin App",
        description = "This is an example Vaadin application.",
        enableInstallPrompt = true)
@CssImport("./styles/shared-styles.css")
@CssImport(value = "./styles/vaadin-text-field-styles.css", themeFor = "vaadin-text-field")
@JavaScript("frontend://js/script.js")
public class MainView extends VerticalLayout{
        /*
        double latitude = 58.3750242;
        double longitude = 26.718989;
        double timeZone = 3;

        final VerticalLayout layout = new VerticalLayout();

        final TextField name = new TextField();
        name.setCaption("Type your name hereeeee:");

        final TextField latitudeText = new TextField();
        latitudeText.setCaption("Enter latitude:");

        final TextField longitudeText = new TextField();
        longitudeText.setCaption("Enter longitude:");

        final TextField timezoneText = new TextField();
        timezoneText.setCaption("Enter timezone");

        Button button = new Button("Calculate sunrise");
        //Button leftButton = new Button("Left", new Icon(VaadinIcon.ARROW_LEFT));



        button.addClickListener(e -> {
            layout.addComponent(new Label(SunriseSunsetCalc.sunrise(Double.parseDouble(latitudeText.getValue()),
                    Double.parseDouble(longitudeText.getValue()),Double.parseDouble(timezoneText.getValue()))));
        });

        layout.addComponents(latitudeText,longitudeText,timezoneText, button);

        setContent(layout);*/

        public MainView() {

            /*
            // Use TextField for standard text input
            TextField textField = new TextField("Your name");

            // Button click listeners can be defined as lambda expressions
            Button button = new Button("Say hello",
                    e -> Notification.show(service.greet(textField.getValue())));

            // Theme variants give you predefined extra styles for components.
            // Example: Primary button is more prominent look.
            button.addThemeVariants(ButtonVariant.LUMO_PRIMARY);

            // You can specify keyboard shortcuts for buttons.
            // Example: Pressing enter in this view clicks the Button.
            button.addClickShortcut(Key.ENTER);

            // Use custom CSS classes to apply styling. This is defined in shared-styles.css.
            addClassName("centered-content");

            ComboBox<String> labelComboBox = new ComboBox<>();
            labelComboBox.setItems("Option one", "Option two");
            labelComboBox.setLabel("Label");

            ComboBox<String> placeHolderComboBox = new ComboBox<>();
            placeHolderComboBox.setItems("Option one", "Option two");
            placeHolderComboBox.setPlaceholder("Placeholder");

            ComboBox<String> valueComboBox = new ComboBox<>();
            valueComboBox.setItems("Value", "Option one", "Option two");
            valueComboBox.setValue("Value");*/
            addClassName("centered-content");

            final VerticalLayout layout = new VerticalLayout();

            final TextField latitudeText = new TextField();
            latitudeText.setLabel("Enter latitude:");

            final TextField longitudeText = new TextField();
            longitudeText.setLabel("Enter longitude:");

            final TextField timezoneText = new TextField();
            timezoneText.setLabel("Enter timezone");

            Button button = new Button("Calculate sunrise");

            Label anwser = new Label("-");

            //Button leftButton = new Button("Left", new Icon(VaadinIcon.ARROW_LEFT));



            button.addClickListener(e -> {
                anwser.setText((SunriseSunsetCalc.sunrise(Double.parseDouble(latitudeText.getValue()),
                        Double.parseDouble(longitudeText.getValue()),Double.parseDouble(timezoneText.getValue()))));
            });


            addClassName("centered-content");

            add(latitudeText,longitudeText,timezoneText, button, anwser);
    }
}
