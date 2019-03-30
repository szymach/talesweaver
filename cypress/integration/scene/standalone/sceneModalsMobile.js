describe('Modal opening', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
        cy.viewport(719, 800);
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul [title="Postacie"]').as('characters');
        cy.get('.side-menu ul [title="Przedmioty"]').as('items');
        cy.get('.side-menu ul [title="Miejsca"]').as('locations');
        cy.get('.side-menu ul [title="Wydarzenia"]').as('events');
    });


    it('opens mobile lists', () => {
        cy.get('@characters').click().then(() => {
            cy.get('.h4').contains('Postacie').should('be.visible');
            cy.get('.h4').contains('Przedmioty').should('not.be.visible');
            cy.get('.h4').contains('Miejsca').should('not.be.visible');
            cy.get('.h4').contains('Wydarzenia').should('not.be.visible');

            cy.get('@characters').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@characters').parent().find('.js-list-container').should('be.visible');
                cy.get('@characters').parent().find('[title="Nowa postać"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowa postać').should('be.visible');
                    cy.get('#clear-ajax').click();
                });
            });
        });

        cy.get('@items').click().then(() => {
            cy.get('.h4').contains('Postacie').should('not.be.visible');
            cy.get('.h4').contains('Przedmioty').should('be.visible');
            cy.get('.h4').contains('Miejsca').should('not.be.visible');
            cy.get('.h4').contains('Wydarzenia').should('not.be.visible');

            cy.get('@items').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@items').parent().find('.js-list-container').should('be.visible');
                cy.get('@items').parent().find('[title="Nowy przedmiot"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowy przedmiot').should('be.visible');
                    cy.get('#clear-ajax').click();
                });
            });
        });

        cy.get('@locations').click().then(() => {
            cy.get('.h4').contains('Postacie').should('not.be.visible');
            cy.get('.h4').contains('Przedmioty').should('not.be.visible');
            cy.get('.h4').contains('Miejsca').should('be.visible');
            cy.get('.h4').contains('Wydarzenia').should('not.be.visible');

            cy.get('@locations').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@locations').parent().find('.js-list-container').should('be.visible');
                cy.get('@locations').parent().find('[title="Nowe miejsce"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowe miejsce').should('be.visible');
                    cy.get('#clear-ajax').click();
                });
            });
        });

        cy.get('@events').click().then(() => {
            cy.get('.h4').contains('Postacie').should('not.be.visible');
            cy.get('.h4').contains('Przedmioty').should('not.be.visible');
            cy.get('.h4').contains('Miejsca').should('not.be.visible');
            cy.get('.h4').contains('Wydarzenia').should('be.visible');

            cy.get('@events').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
                cy.get('@events').parent().find('.js-list-container').should('be.visible');
                cy.get('@events').parent().find('[title="Nowe wydarzenie"]').click().then(() => {
                    cy.get('@ajax-container').contains('Nowe wydarzenie').should('be.visible');
                    cy.get('#clear-ajax').click();
                });
            });
        });
    });
});
