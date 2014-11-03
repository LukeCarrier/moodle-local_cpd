<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Moodle/Totara LMS CPD plugin.
 *
 * A modern replacement for the legacy report_cpd module, which has numerous
 * security issues. Supports the existing tables without any fuss.
 *
 * @author Luke Carrier <luke@carrier.im> <luke@tdm.co>
 * @copyright 2014 Luke Carrier, The Development Manager Ltd
 * @license GPL v3
 */

namespace local_cpd;

use moodle_exception;
use stdClass;

defined('MOODLE_INTERNAL') || die;

/**
 * Base model.
 *
 * The base model class provides basic data access methods and is designed to
 * enable wrapping the "raw" database tables.
 */
abstract class base_model {
    /**
     * Get a property's value.
     *
     * @param string $property The name of the property to get.
     *
     * @return mixed The property's value.
     *
     * @throws \moodle_exception Raises an exception on attempts to access
     *                           properties which aren't mapped to model fields.
     */
    final public function __get($property) {
        if (static::model_has_accessor($property)) {
            $accessor = array($this, "model_accessor_{$property}");
            return call_user_func($accessor);
        } elseif (static::model_has_field($property)) {
            return $this->{$property};
        }

        throw new moodle_exception('privatepropertyaccess', 'local_cpd');
    }

    /**
     * Check if a property's value has been set.
     *
     * @param string $property The name of the property to check.
     *
     * @return boolean True if the property's value has been set, else false.
     */
    final public function __isset($property) {
        return ($this->{$property} !== null);
    }

    /**
     * Set a property's value.
     *
     * @param string $property The name of the property to set.
     * @param mixed  $value    The property's new value.
     *
     * @return void
     */
    final public function __set($property, $value) {
        $this->{$property} = $value;
    }

    /**
     * Handle a call to an undefined static method.
     *
     * @param string  $method The static method that was called.
     * @param mixed[] The set of arguments that the static method was called
     *                with.
     *
     * @return mixed The return value of the called method.
     *
     * @throws \moodle_exception When passed a method name cannot be mapped to
     *                           an appropriate method.
     */
    final public static function __callStatic($method, $arguments) {
        if ($field = util::starts_with($method, 'get_by_')) {
            return static::get(array($field => $arguments[0]));
        } elseif ($field = util::starts_with($method, 'find_by_')) {
            return static::find(array($field => $arguments[0]));
        }

        throw new moodle_exception('nosuchmethod', 'local_cpd', $method);
    }

    /**
     * Return all records in the table.
     *
     * @param string $sort Optional, the name of the field to sort the result
     *                     set by.
     *
     * @return \local_cpd\base_model[] An array of model objects representing
     *                                 the records in the table.
     */
    final public static function all($sort=null) {
        global $DB;

        $records = $DB->get_records(static::model_table(), null, $sort);

        foreach ($records as $id => $record) {
            $records[$id] = static::model_from_dml($record);
        }

        return $records;
    }

    /**
     * Delete a record and all of its children.
     *
     * @return void
     */
    public function delete() {
        global $DB;

        $DB->delete_records(static::model_table(), array(
            'id' => $this->id,
        ));
    }

    /**
     * Find objects matching the given criteria.
     *
     * @param mixed[] $criteria The criteria with which to populate a WHERE
     *                          clause.
     *
     * @return \local_cpd\base_model[] An array of objects, all subclasses of
     *                                 base_model.
     */
    final public static function find($criteria) {
        global $DB;

        $records = $DB->get_records(static::model_table(), $criteria);
        $records = is_array($records) ? $records : array(); // 0 records = false

        return static::model_from_many_dml($records);
    }

    /**
     * Find objects the given complex criteria.
     *
     * This method is like base_model::find(), except that it allows you to use
     * SQL operators in your query.
     *
     * @param string  $select A parameterised SQL string.
     * @param mixed[] $params Parameters to substitute into the query.
     * @param string  $sort   SQL fragment to order the result set by.
     *
     * @return \local_cpd\base_model An array of objects, all subclasses of
     *                               base_model.
     */
    final public static function find_select($select, $params=null, $sort=null) {
        global $DB;

        if ($params === null) {
            $params = array();
        }

        $records = $DB->get_records_select(static::model_table(), $select,
                                           $params, $sort);
        $records = is_array($records) ? $records : array(); // 0 records = false

        return static::model_from_many_dml($records);
    }

    /**
     * Get a single object matching the given criteria.
     *
     * @param mixed $criteria[] The criteria with which to populate a WHERE
     *                          clause.
     *
     * @return \local_cpd\base_model An individual model object.
     */
    final public static function get($criteria) {
        global $DB;

        $record = $DB->get_record(static::model_table(), $criteria, '*',
                                  MUST_EXIST);

        return static::model_from_dml($record);
    }

    /**
     * Get a single object from a complex match.
     *
     * This method is like base_model::get(), except that it allows you to use
     * SQL operators in your query.
     *
     * @param string  $select A parameterised SQL string.
     * @param mixed[] $params Parameters to substitute into the query.
     *
     * @return \local_cpd\base_model An individual model object.
     */
    final public static function get_select($select, $params) {
        global $DB;

        $record = $DB->get_record_select(static::model_table(), $select,
                                         $params, '*', MUST_EXIST);

        return static::model_from_dml($record);
    }

    /**
     * Retrieve an array of model accessors.
     *
     * @return string[] The names of the accessors.
     *
     * @throws \moodle_exception This static method must be implemented in a
     *                           subclass.
     */
    protected static function model_accessors() {
        static::model_throw_incomplete();
    }

    /**
     * Does the model contain an accessor with this name?
     *
     * @param string $accessor The name of the accessor we're checking.
     *
     * @return boolean True if the accessor exists, else false.
     */
    final public static function model_has_accessor($accessor) {
        return in_array($accessor, static::model_accessors());
    }

    /**
     * Does the model contain a field with this name?
     *
     * @param string $field The name of the field we're checking.
     *
     * @return boolean True if the field exists, else false.
     */
    final public static function model_has_field($field) {
        return in_array($field, static::model_fields());
    }

    /**
     * Retrieve an array of model fields.
     *
     * @return string[] The names of the fields.
     * @throws \moodle_exception This static method must be implemented in a
     *                           subclass.
     */
    public static function model_fields() {
        static::model_throw_incomplete();
    }

    /**
     * Create a model instance from a DML record object.
     *
     * @param \stdClass $record The record to retrieve.
     *
     * @return \local_cpd\base_model A model object representing the DML
     *                               record's data.
     */
    final public static function model_from_dml($record) {
        $model = new static();
        $model->model_populate($record);

        return $model;
    }

    /**
     * Given an array of DML records, assemble model objects from all of them.
     *
     * @param \stdClass[] $records An array of DML record objects.
     *
     * @return \local_cpd\base_model[] An array of model objects.
     */
    final public static function model_from_many_dml($records) {
        foreach ($records as $id => $record) {
            $records[$id] = static::model_from_dml($record);
        }

        return $records;
    }

    /**
     * Populate a model from values obtained from a Moodle form.
     *
     * This method *MUST* be overridden in your in your child class if you
     * intend to use it.
     *
     * @param \stdClass $data A data object from moodleform::get_data().
     *
     * @return \local_cpd\base_model A model object.
     */
    public static function model_from_form($data) {
        static::model_throw_incomplete();
    }

    /**
     * Populate a model object from a DML record.
     *
     * @param \stdClass $record The DML record object.
     *
     * @return void
     */
    final public function model_populate($record) {
        foreach (static::model_fields() as $field) {
            $this->{$field} = $record->{$field};
        }
    }

    /**
     * Primary key field.
     *
     * Moodle expects the first field fetched in any DML query to contain a
     * row-unique value, as it uses this field's value in any resulting arrays.
     *
     * You probably shouldn't override this.
     *
     * @return string The name of the table's primary key.
     */
    public static function model_primary_key() {
        return 'id';
    }

    /**
     * Throw an "incomplete model" exception.
     *
     * @throws \moodle_exception Always.
     */
    protected static function model_throw_incomplete() {
        throw new moodle_exception('model:incompleteimplementation',
                                   'local_cpd', '', get_called_class());
    }

    /**
     * Export an array containing field-value pairs from the model object.
     *
     * @param string[] $fields The fields to export. If not passed, every field
     *                         will be exported.
     *
     * @return array An array representing the model object.
     */
    final public function model_to_array($fields=null) {
        $fields = is_array($fields) ? $fields : static::model_fields();

        $array = array();
        foreach ($fields as $field) {
            $array[$field] = $this->{$field};
        }

        return $array;
    }

    /**
     * Export a DML object containing field-value pairs from the model object.
     *
     * @return \stdClass A DML record object representing the model object.
     */
    final public function model_to_dml() {
        $record = new stdClass();
        foreach (static::model_fields() as $field) {
            $record->{$field} = $this->{$field};
        }

        return $record;
    }

    /**
     * model_to_dml(), but for moodleforms.
     *
     * @return \stdClass An object containing the moodleform values. Pass this to
     *                  set_data().
     */
    public function model_to_form() {
        return $this->model_to_dml();
    }

    /**
     * Retrieve the name of the underlying table within the Moodle database.
     *
     * @return string The name of the table.
     * @throws \moodle_exception This static method must be implemented in a
     *                           subclass.
     */
    public static function model_table() {
        static::model_throw_incomplete();
    }

    /**
     * Save amendments to or a new record for the model.
     *
     * When saving a new record, the ID property of the model object will be set
     * on save.
     *
     * @return void
     */
    public function save() {
        global $DB;

        $primarykey = static::model_primary_key();
        $record     = $this->model_to_dml();
        if ($this->{$primarykey} !== null) {
            $DB->update_record(static::model_table(), $record);
        } else {
            $this->{$primarykey} = $DB->insert_record(static::model_table(),
                                                      $record);
        }
    }
}
